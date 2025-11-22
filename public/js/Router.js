// Hacer que Router esté disponible globalmente
window.Router = class Router {
    constructor() {
        this.routes = {
            '/': 'HomePage',
            '/peliculas': 'MoviesPage',
            '/peliculas/:id': 'MovieDetailPage',
            '/login': 'LoginPage',
            '/register': 'RegisterPage',
        };
        
        this.currentPage = null;
        
        // Escuchar cambios de URL
        window.addEventListener('popstate', this.handleRouteChange.bind(this));
        
        // Inicializar la ruta actual
        this.handleRouteChange();
    }
    
    async navigate(path) {
        history.pushState({}, '', path);
        await this.handleRouteChange();
    }
    
    async handleRouteChange() {
        const path = window.location.pathname;
        const routeName = this.getRouteName(path);
        
        if (routeName) {
            await this.loadAndRenderPage(routeName, path);
        } else {
            // Página no encontrada
            document.getElementById('dynamic-content').innerHTML = '<div class="error">Página no encontrada</div>';
        }
    }
    
    getRouteName(path) {
        // Verificamos rutas exactas primero
        if (this.routes[path]) {
            return this.routes[path];
        }
        
        // Luego verificamos rutas con parámetros
        for (const [route, pageName] of Object.entries(this.routes)) {
            if (route.includes(':id')) {
                const routeRegex = new RegExp(route.replace(':id', '\\d+'));
                if (routeRegex.test(path)) {
                    return pageName;
                }
            }
        }
        
        return null;
    }
    
    async loadAndRenderPage(pageName, path) {
        try {
            // Cargar dinámicamente el componente de la página
            await this.loadComponent(`/pages/${this.getPagePath(pageName)}/${pageName}.js`);

            // Obtener la clase del componente
            const PageClass = window[this.getComponentClassName(pageName)];
            if (!PageClass) {
                throw new Error(`Clase ${pageName} no encontrada en el módulo`);
            }

            const pageInstance = new PageClass();

            // Si es una ruta con parámetros, extraerlos
            let data = null;
            if (path.includes('/peliculas/') && path !== '/peliculas') {
                const id = path.split('/').pop();
                // Obtener datos específicos para la página de detalle
                const response = await fetch(`/api/movie/${id}`);
                data = await response.json();
            } else if (path === '/peliculas') {
                // Obtener datos para la página de películas
                const response = await fetch('/api/movies');
                data = await response.json();
            } else if (path === '/') {
                // Obtener datos para la página de inicio
                const response = await fetch('/api/home');
                data = await response.json();
            }

            const html = await pageInstance.render(data);
            document.getElementById('dynamic-content').innerHTML = html;
        } catch (error) {
            console.error('Error al cargar la página:', error);
            document.getElementById('dynamic-content').innerHTML = '<div class="error">Error al cargar la página</div>';
        }
    }

    async loadComponent(url) {
        const script = document.createElement('script');
        script.src = url;
        script.type = 'text/javascript';
        script.async = false; // Necesario para que se ejecute antes de continuar

        return new Promise((resolve, reject) => {
            script.onload = () => {
                // Pequeño delay para asegurar que el componente esté disponible
                setTimeout(() => resolve(), 100);
            };
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    getComponentClassName(pageName) {
        // Convierte el nombre de página en nombre de clase (ej: HomePage -> HomePage)
        return pageName;
    }
    
    getPagePath(pageName) {
        // Convertimos el nombre de la clase a formato de directorio
        // Ej: HomePage -> home, MoviesPage -> movies
        return pageName
            .replace(/([A-Z])/g, '-$1')
            .substring(1)
            .toLowerCase();
    }
    
    // Método para obtener parámetros de la URL
    getUrlParams(path) {
        if (path.includes('/peliculas/') && path !== '/peliculas') {
            const id = path.split('/').pop();
            return { id: parseInt(id) };
        }
        return {};
    }
}