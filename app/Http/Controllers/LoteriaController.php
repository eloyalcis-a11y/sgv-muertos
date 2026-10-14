<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class LoteriaController extends Controller
{
    /** Facetas disponibles */
    public const FACETAS = [
        'graciosa' => 'Graciosa / Gracioso',
        'mandona' => 'Mandona / Mandón',
        'chillona' => 'Chillona / Chillón',
        'romantica' => 'Romántica / Romántico',
        'apapachadora' => 'Apapachadora / Apapachador',
        'alcahueta' => 'Alcahueta / Alcahuete',
        'detallista' => 'Detallista',
        'consentidora' => 'Consentidora / Consentidor',
        'bailadora' => 'Bailadora / Bailador',
        'cantante' => 'Cantante',
        'fiestera' => 'Fiestera / Fiestero',
        'regañona' => 'Regañona / Regañón',
        'enojona' => 'Enojona / Enojón',
        'seria' => 'Seria / Serio',
        'consejera' => 'Consejera / Consejero',
        'protectora' => 'Protectora / Protector',
        'terca' => 'Terca / Terco',
        'perfeccionista' => 'Perfeccionista',
        'chismosa' => 'Chismosa / Chismoso',
        'dormilona' => 'Dormilona / Dormilón',
    ];

    public function create(Request $request)
    {
        $leadId = $request->session()->get('lead_id');
        $lead = $leadId ? Lead::find($leadId) : null;

        // ?paso=N permite abrir directamente un paso (útil para revisar el diseño)
        $pasoParam = $request->query('paso');
        if ($pasoParam === 'gracias' || $pasoParam === '4') {
            return redirect()->route('loteria.gracias');
        }
        $paso = (int) ($pasoParam ?: ($lead ? 3 : 1));

        return view('crear', [
            'facetas' => self::FACETAS,
            'lead' => $lead,
            'paso' => max(1, min(3, $paso)),
        ]);
    }

    /** Paso 2: guarda el registro con las facetas para asegurar el lead completo. */
    public function registro(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'telefono' => ['required', 'regex:/^[0-9 +()-]{10,20}$/'],
            'email' => ['required', 'email', 'max:120'],
            'terminos' => ['accepted'],
            'marketing' => ['nullable', 'boolean'],
            'apodo' => ['nullable', 'string', 'max:60'],
            'facetas' => ['nullable', 'array'],
            'facetas.*' => ['in:'.implode(',', array_keys(self::FACETAS))],
        ]);
        $data['terminos'] = true;
        $data['marketing'] = $request->boolean('marketing');

        $leadId = $request->session()->get('lead_id');
        $lead = $leadId ? Lead::find($leadId) : null;
        $lead ? $lead->update($data) : $lead = Lead::create($data);

        $request->session()->put('lead_id', $lead->id);

        return response()->json(['ok' => true]);
    }

    /** Formulario completo del wizard */
    public function store(Request $request)
    {
        $data = $request->validate([
            'apodo' => ['required', 'string', 'max:60'],
            'facetas' => ['required', 'array', 'min:1'],
            'facetas.*' => ['in:'.implode(',', array_keys(self::FACETAS))],
            'foto' => ['required', 'image', 'max:10240'],
            'nombre' => ['required', 'string', 'max:120'],
            'telefono' => ['required', 'regex:/^[0-9 +()-]{10,20}$/'],
            'email' => ['required', 'email', 'max:120'],
            'terminos' => ['accepted'],
            'marketing' => ['nullable', 'boolean'],
        ]);

        $leadId = $request->session()->get('lead_id');
        $lead = $leadId ? Lead::find($leadId) : null;

        $payload = [
            'nombre' => $data['nombre'],
            'telefono' => $data['telefono'],
            'email' => $data['email'],
            'terminos' => true,
            'marketing' => $request->boolean('marketing'),
            'apodo' => $data['apodo'],
            'facetas' => $data['facetas'],
            'foto' => $request->file('foto')->store('fotos', 'public'),
            'completado_at' => now(),
        ];

        if ($lead) {
            $lead->update($payload);
        } else {
            $lead = Lead::create($payload);
        }

        $request->session()->forget('lead_id');

        // TODO: generar la ilustración y enviar la lotería por correo.

        return redirect()->route('loteria.gracias')->with('ok', $lead->apodo);
    }

    /** Pantalla final de agradecimiento */
    public function gracias(Request $request)
    {
        return view('gracias', [
            'apodo' => $request->session()->get('ok'),
        ]);
    }
}
