import ReactDOM from 'react-dom/client'
import '@/index.css'
import { DataProvider } from '@/DataContext';
import App from '@/App';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'

const queryClient = new QueryClient();

ReactDOM.createRoot(document.getElementById('root')).render(
    <QueryClientProvider client={queryClient}>
        <DataProvider>
          <div style={{"--tools-theme-primary": import.meta.env.VITE_THEME_COLOR}} id="appThemeWrapper">
            <App />
          </div>
        </DataProvider>
    </QueryClientProvider>
)

document.body.style.setProperty('--tools-theme-primary', import.meta.env.VITE_THEME_COLOR);

window.rainbow = () => {
  let hue = 0;
  let interval = setInterval(() => {
    let targetEl = document.getElementById('appThemeWrapper');
    targetEl.style.setProperty('--tools-theme-primary', `hsl(${hue}, 100%, 40%)`);
    hue = (hue + 1) % 360;
  }, 25);

  return () => clearInterval(interval);
}
