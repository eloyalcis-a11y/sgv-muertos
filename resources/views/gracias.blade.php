<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>¡Gracias por compartir sus recuerdos! | Santa Gloria Velatorios</title>
    <meta name="description" content="Tu Lotería de Recuerdos será enviada al WhatsApp que registraste para que puedas guardarla, imprimirla y jugarla en familia.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/gracias.css">
</head>
<body>
    {{-- Flores decorativas en esquinas --}}
    <img class="flor-decor flor-decor--top-der" src="/img/flor.webp" alt="" aria-hidden="true">
    <img class="flor-decor flor-decor--bot-izq" src="/img/flor-2.webp" alt="" aria-hidden="true">

    <main class="gracias-page">
        <div class="gracias-center">
            {{-- Ícono Santa Gloria (Paloma) --}}
            <img class="gracias__dove" src="/img/icono-paloma.webp" alt="Santa Gloria Velatorios">

            {{-- Título gráfico Lotería de Recuerdos --}}
            <div class="gracias__logo">
                <img src="/img/titulo-loteria.webp" alt="Lotería de Recuerdos">
            </div>

            {{-- Mensaje de agradecimiento --}}
            <h1 class="gracias__title">
                Gracias por compartir todas esas versiones que hicieron tan especial a tu ser querido.
            </h1>

            <p class="gracias__description">
                Tu Lotería de Recuerdos será enviada al WhatsApp que registraste para que puedas guardarla, imprimirla, jugarla en familia y seguir recordando todas esas facetas que formaron parte de su historia.
            </p>

            {{-- Redes sociales --}}
            <div class="gracias__social">
                <p class="gracias__social-label">Síguenos en:</p>
                <p class="gracias__social-item">
                    Facebook: <a href="https://www.facebook.com/santagloriavelatorios" target="_blank" rel="noopener noreferrer">Santa Gloria Velatorios</a>
                </p>
                <p class="gracias__social-item">
                    Instagram: <a href="https://www.instagram.com/santagloriavelatorios" target="_blank" rel="noopener noreferrer">@santagloriavelatorios</a>
                </p>
            </div>

            {{-- Botones de acción --}}
            <div class="gracias__actions">
                <a href="{{ route('home') }}" class="btn-loteria" id="btn-jugar">Jugar ahora</a>
                <a href="{{ route('home') }}" class="btn-loteria" id="btn-cerrar">Cerrar</a>
            </div>
        </div>

        {{-- Nota al pie --}}
        <p class="gracias__footnote">
            (SE ENTREGARÁN 6 CARTAS PERSONALIZADAS DEL SER QUERIDO Y EL RESTANTE SON LAS BÁSICAS DE LOTERIA).
        </p>
    </main>
</body>
</html>
