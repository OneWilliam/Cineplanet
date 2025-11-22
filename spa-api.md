# Sistema SPA y API - Cineplanet

## Arquitectura General

El proyecto utiliza una **arquitectura SPA (Single Page Application)** con **Alpine.js** para la reactividad y un **sistema de componentes modular** que se comunica con una **API REST en PHP**.

### Estructura del Sistema

```
public/
├── js/
│   ├── app.js                 # Entry point
│   ├── core/
│   │   ├── Component.js       # Clase base para componentes
│   │   ├── Router.js          # Sistema de routing
│   │   └── API.js             # Cliente API
│   ├── components/            # Componentes reutilizables
│   ├── pages/                 # Páginas/vistas
│   └── layouts/               # Layouts (MainLayout, AuthLayout)
├── components/                # Componentes HTML/CSS
└── pages/                     # Páginas HTML estáticas
```

## 🎯 Cómo Crear un Nuevo Componente

### 1. Crear el Componente

**Ubicación:** `public/js/components/NombreComponente.js`

```javascript
// Ejemplo: MovieList.js
import { Component } from '../core/Component.js';

export class MovieList extends Component {
    constructor(containerId, props = {}) {
        super(containerId, props);
        this.movies = [];
        this.loading = false;
        this.error = null;
    }

    // Datos reactivos de Alpine.js
    data() {
        return {
            movies: this.movies,
            loading: this.loading,
            error: this.error,
            
            // Métodos que puede usar la vista
            async loadMovies() {
                this.loading = true;
                this.error = null;
                
                try {
                    const response = await this.api.get('/movies');
                    this.movies = response.data || [];
                } catch (error) {
                    this.error = 'Error al cargar películas';
                    console.error('Error:', error);
                } finally {
                    this.loading = false;
                }
            },

            async addToWishlist(movieId) {
                try {
                    await this.api.post(`/movies/${movieId}/wishlist`);
                    // Actualizar estado local
                    const movie = this.movies.find(m => m.id === movieId);
                    if (movie) {
                        movie.inWishlist = true;
                    }
                } catch (error) {
                    console.error('Error adding to wishlist:', error);
                }
            }
        };
    }

    // Template HTML
    template() {
        return `
            <div class="movie-list" x-data="movieListData()">
                <!-- Loading State -->
                <div x-show="loading" class="loading">
                    <p>Cargando películas...</p>
                </div>

                <!-- Error State -->
                <div x-show="error" class="error">
                    <p x-text="error"></p>
                    <button @click="loadMovies()">Reintentar</button>
                </div>

                <!-- Movies Grid -->
                <div x-show="!loading && !error" class="movies-grid">
                    <template x-for="movie in movies" :key="movie.id">
                        <div class="movie-card">
                            <img :src="movie.poster" :alt="movie.titulo">
                            <h3 x-text="movie.titulo"></h3>
                            <p x-text="movie.genero"></p>
                            <button 
                                @click="addToWishlist(movie.id)"
                                :disabled="movie.inWishlist"
                                x-text="movie.inWishlist ? 'En Lista' : 'Agregar'"
                            ></button>
                        </div>
                    </template>
                </div>
            </div>
        `;
    }

    // Inicialización del componente
    async init() {
        await this.loadMovies();
    }

    // Limpiar recursos al destruir
    destroy() {
        // Limpiar event listeners, timers, etc.
        super.destroy();
    }
}
```

### 2. CSS del Componente (Opcional)

**Ubicación:** `public/components/MovieList.css`

```css
.movie-list {
    padding: 2rem;
}

.movies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}

.movie-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 1rem;
    transition: transform 0.2s;
}

.movie-card:hover {
    transform: translateY(-5px);
}

.loading, .error {
    text-align: center;
    padding: 2rem;
}
```

### 3. Usar el Componente en una Página

**Ubicación:** `public/js/pages/MoviesPage.js`

```javascript
import { Page } from '../core/Component.js';
import { MovieList } from '../components/MovieList.js';

export class MoviesPage extends Page {
    constructor() {
        super();
        this.title = 'Películas';
    }

    async render() {
        return `
            <div class="movies-page">
                <h1>Cartelera</h1>
                <div id="movie-list-container"></div>
            </div>
        `;
    }

    async afterRender() {
        // Instanciar el componente después del render
        this.movieList = new MovieList('movie-list-container', {
            category: 'cartelera'
        });
    }
}
```

## 🔗 Sistema de API

### Cliente API Base

**Ubicación:** `public/js/core/API.js`

```javascript
class APIClient {
    constructor(baseURL = '/api') {
        this.baseURL = baseURL;
    }

    async request(endpoint, options = {}) {
        const url = `${this.baseURL}${endpoint}`;
        
        const config = {
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            },
            ...options
        };

        // Agregar token de auth si existe
        const token = localStorage.getItem('auth_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }

        try {
            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Error en la petición');
            }

            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }

    async get(endpoint) {
        return this.request(endpoint, { method: 'GET' });
    }

    async post(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    async put(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    async delete(endpoint) {
        return this.request(endpoint, { method: 'DELETE' });
    }
}

export const api = new APIClient();
```

### Endpoints de la API

**Base URL:** `/api.php`

#### Películas
- `GET /movies` - Listar todas las películas
- `GET /movies/{id}` - Obtener película específica
- `POST /movies` - Crear nueva película (admin)
- `PUT /movies/{id}` - Actualizar película (admin)
- `DELETE /movies/{id}` - Eliminar película (admin)

#### Usuarios
- `POST /auth/login` - Iniciar sesión
- `POST /auth/register` - Registrar usuario
- `POST /auth/logout` - Cerrar sesión
- `GET /auth/me` - Obtener usuario actual

#### Funciones/Horarios
- `GET /movies/{id}/showtimes` - Horarios de una película
- `POST /movies/{id}/showtimes` - Crear horario (admin)

## 📱 Ejemplo Completo: Componente de Horarios

### 1. Componente ShowtimeSelector.js

```javascript
import { Component } from '../core/Component.js';

export class ShowtimeSelector extends Component {
    constructor(containerId, props = {}) {
        super(containerId, props);
        this.movieId = props.movieId;
        this.showtimes = [];
        this.selectedShowtime = null;
        this.loading = false;
    }

    data() {
        return {
            showtimes: this.showtimes,
            selectedShowtime: this.selectedShowtime,
            loading: this.loading,

            async loadShowtimes() {
                if (!this.movieId) return;
                
                this.loading = true;
                try {
                    const response = await this.api.get(`/movies/${this.movieId}/showtimes`);
                    this.showtimes = response.data || [];
                } catch (error) {
                    console.error('Error loading showtimes:', error);
                } finally {
                    this.loading = false;
                }
            },

            selectShowtime(showtime) {
                this.selectedShowtime = showtime;
                // Emitir evento para componente padre
                this.emit('showtime-selected', showtime);
            },

            formatTime(datetime) {
                return new Date(datetime).toLocaleTimeString('es-PE', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        };
    }

    template() {
        return `
            <div class="showtime-selector" x-data="showtimeSelectorData()">
                <h3>Selecciona un horario</h3>
                
                <div x-show="loading">Cargando horarios...</div>
                
                <div x-show="!loading && showtimes.length > 0" class="showtimes-grid">
                    <template x-for="showtime in showtimes" :key="showtime.id">
                        <button 
                            @click="selectShowtime(showtime)"
                            :class="{'selected': selectedShowtime?.id === showtime.id}"
                            class="showtime-btn"
                        >
                            <div x-text="formatTime(showtime.fecha_hora)"></div>
                            <div class="cinema" x-text="showtime.sala"></div>
                        </button>
                    </template>
                </div>

                <div x-show="!loading && showtimes.length === 0">
                    No hay horarios disponibles
                </div>
            </div>
        `;
    }

    async init() {
        await this.loadShowtimes();
    }
}
```

### 2. Usar en MovieDetailPage.js

```javascript
import { Page } from '../core/Component.js';
import { ShowtimeSelector } from '../components/ShowtimeSelector.js';

export class MovieDetailPage extends Page {
    constructor(movieId) {
        super();
        this.movieId = movieId;
        this.movie = null;
    }

    async render() {
        // Cargar datos de la película
        try {
            const response = await this.api.get(`/movies/${this.movieId}`);
            this.movie = response.data;
        } catch (error) {
            return '<div>Error al cargar la película</div>';
        }

        return `
            <div class="movie-detail-page">
                <div class="movie-info">
                    <h1>${this.movie.titulo}</h1>
                    <img src="${this.movie.poster}" alt="${this.movie.titulo}">
                    <p>${this.movie.sinopsis}</p>
                </div>
                
                <div id="showtime-selector-container"></div>
                
                <button id="buy-tickets" style="display: none;">
                    Comprar Entradas
                </button>
            </div>
        `;
    }

    async afterRender() {
        // Instanciar selector de horarios
        this.showtimeSelector = new ShowtimeSelector('showtime-selector-container', {
            movieId: this.movieId
        });

        // Escuchar cuando se selecciona un horario
        this.showtimeSelector.on('showtime-selected', (showtime) => {
            console.log('Horario seleccionado:', showtime);
            document.getElementById('buy-tickets').style.display = 'block';
        });
    }
}
```

## 🚀 Integración con Router

### Registrar nuevas rutas

**En:** `public/js/app.js`

```javascript
import { Router } from './core/Router.js';
import { MoviesPage } from './pages/MoviesPage.js';
import { MovieDetailPage } from './pages/MovieDetailPage.js';

const router = new Router();

// Registrar rutas
router.addRoute('/', () => new HomePage());
router.addRoute('/movies', () => new MoviesPage());
router.addRoute('/movies/:id', (params) => new MovieDetailPage(params.id));
router.addRoute('/booking/:showtimeId', (params) => new BookingPage(params.showtimeId));

// Inicializar router
router.start();
```

## 📋 Checklist para Nuevo Componente

- [ ] Crear clase que extiende `Component`
- [ ] Implementar método `data()` con estado reactivo
- [ ] Implementar método `template()` con HTML
- [ ] Agregar métodos `init()` y `destroy()` si es necesario
- [ ] Crear CSS asociado (opcional)
- [ ] Definir endpoints de API necesarios
- [ ] Implementar manejo de errores y estados de carga
- [ ] Documentar props y eventos del componente
- [ ] Agregar tests (si aplica)

## 🔧 Comandos Útiles para Desarrollo

```bash
# Levantar servidor local
php -S localhost:8000 -t public

# Ver logs de API
tail -f /var/log/apache2/error.log

# Inspeccionar base de datos
mysql -u root -p cineplanet
```

## 📚 Recursos Adicionales

- [Alpine.js Documentación](https://alpinejs.dev/)
- [Fetch API MDN](https://developer.mozilla.org/es/docs/Web/API/Fetch_API)
- [PHP Slim Framework](https://www.slimframework.com/)

---

**Última actualización:** 22 de Noviembre, 2024