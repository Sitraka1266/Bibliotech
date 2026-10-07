<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdherentRequest;
use App\Models\Adherent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class AdherentController extends Controller
{
    /** F2 */
    public function index(Request $request)
    {
        $adherents = Adherent::query()
            ->withCount(['emprunts as emprunts_en_cours_count' => fn ($q) => $q->whereNull('date_retour_effective')])
            ->when($request->filled('q'), function ($query) use ($request) {
                $s = trim($request->q);
                $query->where(fn ($w) => $w->where('nom', 'like', "%{$s}%")
                    ->orWhere('prenom', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%"));
            })
            ->orderBy('nom')
            ->paginate(10)
            ->withQueryString();

        return view('adherents.index', compact('adherents'));
    }

    public function create()
    {
        return view('adherents.create', ['adherent' => new Adherent(['date_inscription' => today()])]);
    }

    public function store(AdherentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['photo']);

        $photo = $request->file('photo');
        if ($photo instanceof UploadedFile) {
            $data['photo_path'] = $this->storeProfilePhoto($photo);
        }

        Adherent::create($data);

        return redirect()->route('adherents.index')->with('success', 'Adhérent ajouté avec succès.');
    }

    /** F11 : fiche + historique des emprunts */
    public function show(Adherent $adherent)
    {
        $emprunts = $adherent->emprunts()->with('livre')->orderByDesc('date_emprunt')->orderByDesc('id')->get();

        return view('adherents.show', compact('adherent', 'emprunts'));
    }

    public function edit(Adherent $adherent)
    {
        return view('adherents.edit', compact('adherent'));
    }

    public function update(AdherentRequest $request, Adherent $adherent): RedirectResponse
    {
        $data = $request->validated();
        unset($data['photo']);

        $photo = $request->file('photo');
        if ($photo instanceof UploadedFile) {
            $data['photo_path'] = $this->storeProfilePhoto($photo);
        }

        $oldPhotoPath = $adherent->photo_path;
        $adherent->update($data);

        if (isset($data['photo_path'])) {
            $this->deleteProfilePhoto($oldPhotoPath, $adherent->id);
        }

        return redirect()->route('adherents.index')->with('success', 'Adhérent modifié avec succès.');
    }

    private function storeProfilePhoto(UploadedFile $photo): string
    {
        $path = $photo->store('adherents', 'public');

        if ($path === false) {
            throw new RuntimeException('Impossible de stocker la photo de profil de l’adhérent.');
        }

        return $path;
    }

    private function deleteProfilePhoto(?string $path, int $adherentId): void
    {
        if ($path !== null && ! Storage::disk('public')->delete($path)) {
            Log::warning('Unable to remove adherent profile photo.', [
                'adherent_id' => $adherentId,
                'photo_path' => $path,
            ]);
        }
    }

    public function destroy(Adherent $adherent): RedirectResponse
    {
        // Règle 5
        if ($adherent->emprunts()->enCours()->exists()) {
            return back()->withErrors(['suppression' => "Suppression impossible : {$adherent->nomComplet()} a des emprunts en cours."]);
        }
        $photoPath = $adherent->photo_path;
        $adherent->delete();
        $this->deleteProfilePhoto($photoPath, $adherent->id);

        return redirect()->route('adherents.index')->with('success', 'Adhérent supprimé.');
    }
}
