<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crea tu Lotería de Recuerdos | Santa Gloria Velatorios</title>
    <meta name="description" content="Selecciona las facetas de tu ser querido, sube su foto y crea una lotería de recuerdos para jugar en familia.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/landing.css">
    <link rel="stylesheet" href="/css/crear.css">
</head>
<body>
<main class="wizard-page">
    <img class="flor flor--top-der" src="/img/flor.webp" alt="" aria-hidden="true">
    <img class="flor flor--bot-izq" src="/img/flor-2.webp" alt="" aria-hidden="true">

    <header class="wz-header">
        <a href="{{ route('home') }}" aria-label="Inicio">
            <img class="wz-logo" src="/img/logo-santa-gloria.webp" alt="Santa Gloria Velatorios">
        </a>
        <h1 class="sr-only">Crea tu Lotería de Recuerdos</h1>
        
        <div class="steps-wrapper">
            <div class="steps-bar" aria-hidden="true">
                <div class="steps-bar__progress" id="steps-progress"></div>
            </div>
            <ol class="steps" aria-label="Progreso">
                <li class="is-active" data-dot="1"><span>1</span>Sus facetas</li>
                <li data-dot="2"><span>2</span>Regístrate</li>
                <li data-dot="3"><span>3</span>Su foto</li>
            </ol>
        </div>
    </header>

    @if (session('ok'))
        <section class="wz-card wz-done">
            <h2>¡Gracias!</h2>
            <p>Estamos creando la lotería de recuerdos de <strong>{{ session('ok') }}</strong>. Te la enviaremos a tu correo.</p>
            <a class="btn-start" href="{{ route('home') }}">Volver al inicio</a>
        </section>
    @else
    <form class="wz" id="wizard" method="POST" action="{{ route('loteria.store') }}" enctype="multipart/form-data" novalidate>
        @csrf

        @if ($errors->any())
            <div class="wz-errors" role="alert">Revisa los campos marcados: {{ $errors->first() }}</div>
        @endif

        <div class="wz-grid">
            {{-- Paso 1: Facetas --}}
            <section class="wz-step is-active" data-step="1" id="step-facetas">
                <h2 class="wz-title">Sus facetas</h2>
                <p class="wz-intro">Todos conocimos una versión distinta de esa persona. Selecciona sus diferentes facetas y crearemos una lotería inspirada en todas ellas para que puedas jugar con tu familia.</p>

                <label class="field">
                    <span>Nombre o apodo de tu ser querido*</span>
                    <input type="text" name="apodo" id="apodo" value="{{ old('apodo', $lead?->apodo) }}" maxlength="60" placeholder="Ej. Mi abuelita Chayo, El Tigre, Papá..." required>
                </label>

                <fieldset class="facetas">
                    <legend>
                        <span>Elige sus facetas*</span>
                        <span class="badge-count" id="faceta-count">0 seleccionadas</span>
                    </legend>
                    <div class="facetas-grid">
                        @foreach ($facetas as $key => $label)
                            <label class="chip">
                                <input type="checkbox" name="facetas[]" value="{{ $key }}" @checked(in_array($key, old('facetas', $lead?->facetas ?? [])))>
                                <span>
                                    <span class="chip-icon" aria-hidden="true">
                                        <svg viewBox="0 0 16 16" fill="none"><path d="M3 8l3 3 7-7" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </span>
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            </section>

            {{-- Paso 2: Registro (se guarda al dar "Siguiente") --}}
            <section class="wz-step" data-step="2" id="step-registro">
                <h2 class="wz-title">Regístrate</h2>
                <p class="wz-intro">Déjanos tus datos para enviarte tu lotería de recuerdos a WhatsApp y correo.</p>
                
                <label class="field">
                    <span>Nombre completo*</span>
                    <input type="text" name="nombre" value="{{ old('nombre', $lead?->nombre) }}" placeholder="Tu nombre y apellido" autocomplete="name" required>
                </label>
                
                <label class="field">
                    <span>Teléfono (WhatsApp)*</span>
                    <input type="tel" name="telefono" value="{{ old('telefono', $lead?->telefono) }}" placeholder="10 dígitos (ej. 55 1234 5678)" autocomplete="tel" inputmode="tel" pattern="[0-9 +()\-]{10,20}" required>
                </label>
                
                <label class="field">
                    <span>Correo electrónico*</span>
                    <input type="email" name="email" value="{{ old('email', $lead?->email) }}" placeholder="ejemplo@correo.com" autocomplete="email" required>
                </label>

                <label class="check">
                    <input type="checkbox" name="terminos" value="1" required @checked(old('terminos', $lead?->terminos))>
                    <span>Acepto los términos y condiciones del sitio.</span>
                </label>
                
                <label class="check">
                    <input type="checkbox" name="marketing" value="1" @checked(old('marketing', $lead?->marketing))>
                    <span>Acepto recibir información sobre Servicios Funerarios a Futuro.</span>
                </label>
                
                <a class="wz-link" href="#" id="aviso-privacidad">Consulta nuestro aviso de privacidad.</a>
                <p class="wz-note">*Asegúrate de que tus datos sean correctos para hacerte llegar tu lotería digital.</p>
            </section>

            {{-- Paso 3: Foto --}}
            <section class="wz-step" data-step="3" id="step-foto">
                <h2 class="wz-title">Su foto</h2>
                <p class="wz-intro">Sube una foto de tu ser querido y la transformaremos en una ilustración para crear su propia lotería de recuerdos.</p>

                <label class="dropzone" id="dropzone">
                    <input type="file" name="foto" id="foto" accept="image/*" required>
                    <div class="dropzone__icon" aria-hidden="true">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                    </div>
                    <div class="dropzone__msg" id="drop-msg">
                        <strong>Arrastra su foto aquí</strong>
                        <span>o haz clic para elegirla desde tu dispositivo</span>
                        <div class="dropzone__tags">
                            <span class="dropzone__tag">JPG</span>
                            <span class="dropzone__tag">PNG</span>
                            <span class="dropzone__tag">WEBP</span>
                            <span class="dropzone__tag">Máx. 10 MB</span>
                        </div>
                        <p class="dropzone__tip">✨ Tip: De preferencia que sea de frente y bien iluminada</p>
                    </div>
                    <div class="dropzone-preview-wrap" id="drop-preview-wrap" hidden>
                        <img id="foto-preview" alt="Vista previa de la foto">
                        <span class="dropzone-change-btn">Cambiar fotografía</span>
                    </div>
                </label>
            </section>

            <aside class="wz-preview" aria-hidden="true">
                <img src="/img/cartas.webp" alt="Cartas de ejemplo">
            </aside>
        </div>

        <p class="wz-error" id="step-error" role="alert" hidden></p>

        <nav class="wz-nav">
            <button type="button" class="btn-ghost" id="btn-prev" hidden>Atrás</button>
            <button type="button" class="btn-start" id="btn-next">Siguiente</button>
            <button type="submit" class="btn-start" id="btn-submit" hidden>Crear mi lotería</button>
        </nav>
    </form>
    @endif
</main>

<script>
(() => {
    const form = document.getElementById('wizard');
    if (!form) return;
    const steps = [...form.querySelectorAll('.wz-step')];
    const dots = [...document.querySelectorAll('.steps li')];
    const progressBar = document.getElementById('steps-progress');
    const prev = document.getElementById('btn-prev');
    const next = document.getElementById('btn-next');
    const submit = document.getElementById('btn-submit');
    const err = document.getElementById('step-error');
    let current = {{ max(0, min(2, ($paso ?? 1) - 1)) }};

    const show = (i) => {
        current = i;
        steps.forEach((s, n) => s.classList.toggle('is-active', n === i));
        dots.forEach((d, n) => {
            d.classList.toggle('is-active', n === i);
            d.classList.toggle('is-done', n < i);
        });
        if (progressBar) {
            progressBar.style.width = (i / (steps.length - 1)) * 100 + '%';
        }
        prev.hidden = i === 0;
        next.hidden = i === steps.length - 1;
        submit.hidden = i !== steps.length - 1;
        err.hidden = true;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    if (current > 0) {
        show(current);
    } else {
        show(0);
    }

    const validate = (i) => {
        const s = steps[i];
        if (i === 0) {
            const apodo = s.querySelector('#apodo');
            if (apodo && !apodo.value.trim()) { apodo.focus(); return 'Escribe el nombre o apodo de tu ser querido.'; }
            if (!s.querySelector('input[name="facetas[]"]:checked')) return 'Selecciona al menos una faceta.';
        }
        for (const input of s.querySelectorAll('input[required]')) {
            if (!input.checkValidity()) { input.focus(); return input.type === 'file' ? 'Sube una foto para continuar.' : 'Completa los campos marcados.'; }
        }
        return null;
    };

    // Guarda el registro en el servidor al completar el paso 2 (asegura el lead con sus facetas)
    const saveRegistro = async () => {
        const fd = new FormData();
        [steps[0], steps[1]].forEach(step => {
            step.querySelectorAll('input').forEach(i => {
                if (i.type === 'checkbox') { if (i.checked) fd.append(i.name, '1'); }
                else if (i.value) fd.append(i.name, i.value);
            });
        });
        const res = await fetch(@json(route('loteria.registro')), {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': form.querySelector('[name=_token]').value, 'Accept': 'application/json' },
            body: fd,
        });
        if (res.ok) return null;
        const body = await res.json().catch(() => ({}));
        return body.errors ? Object.values(body.errors)[0][0] : 'No pudimos guardar tus datos, intenta de nuevo.';
    };

    next.addEventListener('click', async () => {
        let msg = validate(current);
        if (!msg && current === 1) {
            next.disabled = true; next.textContent = 'Guardando…';
            msg = await saveRegistro().catch(() => 'Error de conexión, intenta de nuevo.');
            next.disabled = false; next.textContent = 'Siguiente';
        }
        if (msg) { err.textContent = msg; err.hidden = false; return; }
        show(current + 1);
    });
    prev.addEventListener('click', () => show(current - 1));
    form.addEventListener('submit', (e) => {
        const msg = validate(current);
        if (msg) { e.preventDefault(); err.textContent = msg; err.hidden = false; }
    });

    // Contador dinámico de facetas
    const count = document.getElementById('faceta-count');
    const updateCount = () => {
        const n = form.querySelectorAll('input[name="facetas[]"]:checked').length;
        count.textContent = `${n} seleccionada${n === 1 ? '' : 's'}`;
        count.classList.toggle('has-facetas', n > 0);
    };
    form.addEventListener('change', updateCount);
    updateCount();

    // Foto / Dropzone
    const file = document.getElementById('foto');
    const img = document.getElementById('foto-preview');
    const previewWrap = document.getElementById('drop-preview-wrap');
    const drop = document.getElementById('dropzone');
    const dropMsg = document.getElementById('drop-msg');

    file.addEventListener('change', () => {
        const f = file.files[0];
        if (!f) return;
        img.src = URL.createObjectURL(f);
        previewWrap.hidden = false;
        dropMsg.hidden = true;
    });

    ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, () => drop.classList.add('is-over')));
    ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, () => drop.classList.remove('is-over')));

    // Si el servidor devolvió errores, ir al primer paso con error
    @if ($errors->any())
        @php $s = $errors->hasAny(['apodo','facetas','facetas.*']) ? 0 : ($errors->hasAny(['nombre','telefono','email','terminos','marketing']) ? 1 : 2); @endphp
        show({{ $s }});
    @endif
})();
</script>
</body>
</html>
