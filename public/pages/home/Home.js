class HomePage {
    constructor() {
        this.template = null;
        this.cssLoaded = false;
    }

    async loadTemplate() {
        if (!this.template) {
            const response = await fetch('/pages/home/Home.html');
            this.template = await response.text();
        }
        return this.template;
    }

    async loadCSS() {
        if (!this.cssLoaded) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.type = 'text/css';
            link.href = '/pages/home/Home.css';
            document.head.appendChild(link);
            this.cssLoaded = true;
        }
    }

    async render(data = null) {
        await this.loadCSS();
        const template = await this.loadTemplate();

        // Si se proporcionan datos, remplazamos placeholders
        let html = template;
        if (data && Array.isArray(data)) {
            // Generamos las tarjetas de películas
            let movieCards = '';
            data.forEach(pelicula => {
                const nombre = pelicula.nombre || 'Nombre no disponible';
                const duracion = pelicula.duracion || 'Duración no disponible';
                const imagen = (pelicula.images && pelicula.images.length > 0) ? pelicula.images[0] : '/img/placeholder-movie.jpg';
                const id = pelicula.pelicula_id || 0;

                movieCards += `
                <div class="movie-card">
                    <img src="${imagen}" alt="${nombre}" class="movie-poster">
                    <div class="movie-info">
                        <h3 class="movie-title">${nombre}</h3>
                        <p class="movie-meta">Duración: ${duracion} min</p>
                        <div class="movie-actions">
                            <button class="btn btn-primary"
                                    hx-get="/api/movie/${id}"
                                    hx-target="#dynamic-content"
                                    hx-push-url="true">
                                Ver Detalles
                            </button>
                        </div>
                    </div>
                </div>`;
            });

            // Remplazamos el placeholder con las tarjetas de películas
            html = template.replace('{{movieCards}}', movieCards);
        }

        return html;
    }
}

// Hacer que el componente esté disponible globalmente
window.HomePage = HomePage;