<section class="asistente-section">

    <div class="asistente-card">
        <h2>Peliculas parecidas a...</h2>
        <p class="asistente-desc">Escribe el titulo de una pelicula y la IA te recomendara otras similares en genero, estilo y epoca.</p>
        <div class="asistente-input-row">
            <input type="text" id="similaresInput" placeholder="Ej: Contacto Sangriento, Terminator, El Padrino...">
            <button id="similaresBtn">Buscar similares</button>
        </div>
        <div id="similaresOutput" class="asistente-output" style="display:none;"></div>
    </div>

<div class="asistente-card" style="margin-top:24px;">
    <h2>¿Qué veo esta noche?</h2>
    <p class="asistente-desc">Describe tu mood y la IA te recomienda películas que encajan.</p>
    <div class="asistente-input-row">
        <input type="text" id="moodInput" placeholder="Ej: algo de acción corto, terror psicológico, comedia italiana...">
        <button id="moodBtn">Recomendar</button>
    </div>
    <div id="moodOutput" class="asistente-output" style="display:none;"></div>
</div>
</section>