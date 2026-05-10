
// --- UTILIDADES ---
function getUrlParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

function tmdbImg(path) {
    return path ? TMDB_IMG + path : null;
}

function createMovieCard(movie, miPuntuacion = null) {
    const card = document.createElement('div');
    card.className = 'movie-card';
    card.onclick = () => window.location.href = `index.php?page=pelicula&id=${movie.id}`;

    const year = movie.release_date ? movie.release_date.substring(0, 4) : 'N/A';
    const rating = movie.vote_average ? movie.vote_average.toFixed(1) : 'N/A';
    const posterSrc = tmdbImg(movie.poster_path);

    card.innerHTML = `
        ${posterSrc
            ? `<img src="${posterSrc}" alt="${movie.title}">`
            : `<div class="no-poster">Sin póster</div>`
        }
        <div class="card-info">
            <div class="card-title">${movie.title}</div>
            <div class="card-year">${year}</div>
            <div class="card-rating">Score ${rating}</div>
            ${miPuntuacion ? `<div class="card-mi-puntuacion">Mi nota: ${miPuntuacion}/10</div>` : ''}
        </div>
    `;
    return card;
}

// --- BUSQUEDA ---
function initSearch() {
    const searchBtn = document.getElementById('searchBtn');
    const searchInput = document.getElementById('searchInput');

    if (!searchBtn || !searchInput) return;

    const doSearch = () => {
        const query = searchInput.value.trim();
        if (query.length < 2) return;
        searchMovies(query);
    };

    searchBtn.addEventListener('click', doSearch);
    searchInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') doSearch();
    });
}

async function searchMovies(query) {
    try {
        const res = await fetch(`${TMDB_BASE}/search/movie?api_key=${TMDB_API_KEY}&language=es-ES&query=${encodeURIComponent(query)}`);
        const data = await res.json();

        const currentContent = document.querySelector('.home-section, .list-section, .asistente-section, .movie-detail');
        if (currentContent) currentContent.style.display = 'none';

        const recommendations = document.getElementById('recommendations');
        const trending = document.getElementById('trending');
        if (recommendations) recommendations.style.display = 'none';
        if (trending) trending.style.display = 'none';

        const searchResults = document.getElementById('searchResults');
        const container = document.getElementById('resultsContainer');

        if (searchResults) searchResults.style.display = 'block';
        if (container) {
            container.innerHTML = '';
            if (data.results && data.results.length > 0) {
                data.results.forEach(movie => {
                    container.appendChild(createMovieCard(movie));
                });
            } else {
                container.innerHTML = '<p class="loading">No se encontraron resultados.</p>';
            }
        }
    } catch (err) {
        console.error('Error buscando:', err);
    }
}
// --- dariush RECOMENDACIONES ---
async function loadRecommendations() {
    const grid = document.getElementById('recommendations');
    if (!grid) return;

    const movieIds = [24, 111, 98, 18785];

    grid.innerHTML = '';
    for (const id of movieIds) {
        try {
            const res = await fetch(`${TMDB_BASE}/movie/${id}?api_key=${TMDB_API_KEY}&language=es-ES`);
            const movie = await res.json();
            grid.appendChild(createMovieCard(movie));
        } catch (e) {
            console.error('Error cargando película:', id, e);
        }
    }
}

async function loadTrending() {
    const grid = document.getElementById('trending');
    if (!grid) return;

    try {
        const res = await fetch(`${TMDB_BASE}/movie/popular?api_key=${TMDB_API_KEY}&language=es-ES&page=1`);
        const data = await res.json();
        grid.innerHTML = '';
        data.results.slice(0, 8).forEach(movie => {
            grid.appendChild(createMovieCard(movie));
        });
    } catch (e) {
        console.error('Error cargando tendencias:', e);
    }
}

// --- DETALLE DE PELÍCULA ---
async function loadMovieDetail() {
    const container = document.getElementById('movieDetail');
    if (!container) return;

    const movieId = getUrlParam('id');
    if (!movieId) {
        container.innerHTML = '<p>No se especificó ninguna película.</p>';
        return;
    }

    try {
        const res = await fetch(`${TMDB_BASE}/movie/${movieId}?api_key=${TMDB_API_KEY}&language=es-ES&append_to_response=credits`);
        const movie = await res.json();

        const posterSrc = tmdbImg(movie.poster_path);
        const date = movie.release_date || 'N/A';
        const genres = movie.genres ? movie.genres.map(g => `<span>${g.name}</span>`).join('') : '';
        const rating = movie.vote_average ? movie.vote_average.toFixed(1) : 'N/A';

        container.innerHTML = `
            <div class="movie-detail">
                ${posterSrc
                    ? `<img src="${posterSrc}" alt="${movie.title}">`
                    : `<div class="no-poster" style="width:300px;height:450px">Sin póster</div>`
                }
                <div class="detail-info">
                    <h1>${movie.title}</h1>
                    <div class="detail-date">${date}</div>
                    <div class="detail-genres">${genres}</div>
                    <div class="detail-tmdb-rating"> ${rating} / 10 (SCORE IN TMDB)</div>
                    <p class="detail-overview">${movie.overview || 'Sin sinopsis disponible.'}</p>

                    <div class="detail-actions">
                        <button class="btn-favorito" id="btnFavorito" data-id="${movie.id}">
                             Añadir a Mi Lista
                        </button>
                        <button class="btn-pendiente" id="btnPendiente" data-id="${movie.id}">
                             Pendiente por ver
                        </button>
                    </div>

                    <div class="review-form">
                        <h3>Tu puntuación y comentario</h3>
                        <select id="puntuacionSelect">
                            <option value="">Puntuar 1-10</option>
                            ${[1,2,3,4,5,6,7,8,9,10].map(n => `<option value="${n}">${n}</option>`).join('')}
                        </select>
                        <textarea id="comentarioText" placeholder="Añadir comentario..."></textarea>
                        <button class="btn-submit" id="btnResena">Guardar reseña</button>
                    </div>
                </div>
            </div>
        `;

        // Comprobar estados
        checkFavoritoStatus(movie.id);
        checkPendienteStatus(movie.id);
        loadExistingResena(movie.id);

        // Event listeners
        document.getElementById('btnFavorito').addEventListener('click', () => toggleFavorito(movie.id));
        document.getElementById('btnPendiente').addEventListener('click', () => togglePendiente(movie.id));
        document.getElementById('btnResena').addEventListener('click', () => saveResena(movie.id));

    } catch (err) {
        console.error('Error cargando detalle:', err);
        container.innerHTML = '<p>Error al cargar la película.</p>';
    }
}

// --- FAVORITOS ---
async function checkFavoritoStatus(movieId) {
    const res = await fetch(`controller/MovieController.php?action=is_favorito&movie_id=${movieId}`);
    const data = await res.json();
    const btn = document.getElementById('btnFavorito');
    if (data.is_favorito) {
        btn.classList.add('active');
        btn.innerHTML = ' En Mi Lista';
    }
}

async function toggleFavorito(movieId) {
    const btn = document.getElementById('btnFavorito');
    const isActive = btn.classList.contains('active');
    const action = isActive ? 'remove_favorito' : 'add_favorito';

    const formData = new FormData();
    formData.append('action', action);
    formData.append('movie_id', movieId);

    const res = await fetch('controller/MovieController.php', { method: 'POST', body: formData });
    const data = await res.json();

    if (data.success) {
        btn.classList.toggle('active');
        btn.innerHTML = btn.classList.contains('active') ? ' En Mi Lista' : ' Añadir a Mi Lista';
    }
}

// --- PENDIENTES ---
async function checkPendienteStatus(movieId) {
    const res = await fetch(`controller/MovieController.php?action=is_pendiente&movie_id=${movieId}`);
    const data = await res.json();
    const btn = document.getElementById('btnPendiente');
    if (data.is_pendiente) {
        btn.classList.add('active');
        btn.innerHTML = ' En Pendientes';
    }
}

async function togglePendiente(movieId) {
    const btn = document.getElementById('btnPendiente');
    const isActive = btn.classList.contains('active');
    const action = isActive ? 'remove_pendiente' : 'add_pendiente';

    const formData = new FormData();
    formData.append('action', action);
    formData.append('movie_id', movieId);

    const res = await fetch('controller/MovieController.php', { method: 'POST', body: formData });
    const data = await res.json();

    if (data.success) {
        btn.classList.toggle('active');
        btn.innerHTML = btn.classList.contains('active') ? ' En Pendientes' : ' Pendiente por ver';
    }
}

// --- RESEÑAS ---
async function loadExistingResena(movieId) {
    const res = await fetch(`controller/MovieController.php?action=get_resena&movie_id=${movieId}`);
    const data = await res.json();
    if (data.resena) {
        document.getElementById('puntuacionSelect').value = data.resena.puntuacion || '';
        document.getElementById('comentarioText').value = data.resena.comentario || '';
    }
}

async function saveResena(movieId) {
    const puntuacion = document.getElementById('puntuacionSelect').value;
    const comentario = document.getElementById('comentarioText').value;

    if (!puntuacion) {
        return;
    }

    const formData = new FormData();
    formData.append('action', 'add_resena');
    formData.append('movie_id', movieId);
    formData.append('puntuacion', puntuacion);
    formData.append('comentario', comentario);

    const res = await fetch('controller/MovieController.php', { method: 'POST', body: formData });
    const data = await res.json();

    if (data.success) {
        const btn = document.getElementById('btnResena');
        btn.textContent = '✓ Guardado';
        btn.style.backgroundColor = '#28a745';
        setTimeout(() => {
            btn.textContent = 'Guardar reseña';
            btn.style.backgroundColor = '';
        }, 2000);
    }
}
// --- CARGAR LISTAS (favoritos / pendientes) ---
async function loadMovieList(action, gridId) {
    const grid = document.getElementById(gridId);
    if (!grid) return;

    try {
        const res = await fetch(`controller/MovieController.php?action=${action}`);
        const data = await res.json();

        if (!data.movie_ids || data.movie_ids.length === 0) {
            grid.innerHTML = '<p class="loading">No tienes películas en esta lista.</p>';
            return;
        }

        // Obtener reseñas si es favoritos
        let reseñas = {};
        if (action === 'get_favoritos') {
            const resReseñas = await fetch('controller/MovieController.php?action=get_all_resenas');
            const dataReseñas = await resReseñas.json();
            if (dataReseñas.resenas) {
                dataReseñas.resenas.forEach(r => {
                    reseñas[r.movie_id] = r.puntuacion;
                });
            }
        }

        grid.innerHTML = '';
        for (const id of data.movie_ids) {
            try {
                const movieRes = await fetch(`${TMDB_BASE}/movie/${id}?api_key=${TMDB_API_KEY}&language=es-ES`);
                const movie = await movieRes.json();
                grid.appendChild(createMovieCard(movie, reseñas[id] || null));
            } catch (e) {
                console.error('Error cargando película:', id, e);
            }
        }
    } catch (err) {
        console.error('Error cargando lista:', err);
    }
}

// --- INIT ---
document.addEventListener('DOMContentLoaded', () => {
    const page = getUrlParam('page') || 'home';

    initSearch();

    switch (page) {
        case 'home':
        case null:
            loadRecommendations();
            loadTrending();
            break;
        case 'pelicula':
            loadMovieDetail();
            break;
        case 'favoritos':
            loadMovieList('get_favoritos', 'favoritosGrid');
            break;
        case 'pendientes':
            loadMovieList('get_pendientes', 'pendientesGrid');
            break;
        case 'asistente':
            initAsistente();
            break;
    }
});


// ASISTENTE IA

function initAsistente() {
    const similaresBtn = document.getElementById('similaresBtn');
    const similaresInput = document.getElementById('similaresInput');

    if (similaresBtn && similaresInput) {
        const doSimilares = () => {
            const t = similaresInput.value.trim();
            if (t.length < 2) return;
            pedirSimilares(t);
        };
        similaresBtn.addEventListener('click', doSimilares);
        similaresInput.addEventListener('keydown', e => { if (e.key === 'Enter') doSimilares(); });
    }
    const moodBtn = document.getElementById('moodBtn');
    const moodInput = document.getElementById('moodInput');

    if (moodBtn && moodInput) {
        const doMood = () => {
            const q = moodInput.value.trim();
            if (q.length < 3) return;
            pedirMood(q);
        };
        moodBtn.addEventListener('click', doMood);
        moodInput.addEventListener('keydown', e => { if (e.key === 'Enter') doMood(); });
    }
}

async function pedirSimilares(titulo) {
    const output = document.getElementById('similaresOutput');
    const btn = document.getElementById('similaresBtn');

    output.style.display = 'block';
    output.innerHTML = '<p class="loading">Buscando peliculas similares...</p>';
    btn.disabled = true;
    btn.textContent = 'Buscando...';

    try {
        const formData = new FormData();
        formData.append('action', 'similares');
        formData.append('titulo', titulo);

        const res = await fetch('controller/AIController.php', { method: 'POST', body: formData });
        const data = await res.json();

        let html = data.reply || 'Sin respuesta.';
        html = html
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\n/g, '<br>');

        output.innerHTML = html;

    } catch (err) {
        console.error('Error:', err);
        output.innerHTML = '<p>Error al conectar con el asistente. Intentalo de nuevo.</p>';
    } finally {
        btn.disabled = false;
        btn.textContent = 'Buscar similares';
    }
}


async function pedirMood(mood) {
    const output = document.getElementById('moodOutput');
    const btn = document.getElementById('moodBtn');

    output.style.display = 'block';
    output.innerHTML = '<p class="loading">Buscando películas para tu mood...</p>';
    btn.disabled = true;
    btn.textContent = 'Buscando...';

    try {
        const formData = new FormData();
        formData.append('action', 'mood');
        formData.append('mood', mood);

        const res = await fetch('controller/AIController.php', { method: 'POST', body: formData });
        const data = await res.json();

        let html = data.reply || 'Sin respuesta.';
        html = html
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\n/g, '<br>');

        output.innerHTML = html;

    } catch (err) {
        output.innerHTML = '<p>Error al conectar con el asistente.</p>';
    } finally {
        btn.disabled = false;
        btn.textContent = 'Recomendar';
    }
}