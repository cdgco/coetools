import { useContext } from 'react';
import { DefaultLayout } from '@/layouts';
import { DataContext } from '@/DataContext';
import { Cards } from '@/components'; 
import { cardLoader } from '@/extensions';

function Home() {
  const { data, nightMode } = useContext(DataContext);
  const name = (data.user.name) ? data.user.name.split(' ')[0] : data.user.username

    // If the user clicks on "COE Tools" 5 times in a row within 5 seconds, enable rainbow mode
    let clicks = 0;
    let timeout;
    const rainbowMode = () => {
      clicks++;
      if (clicks === 1) {
        timeout = setTimeout(() => {
          clicks = 0;
        }, 5000);
      } else if (clicks === 5) {
        clearTimeout(timeout);
        window.rainbow();
        clicks = 0;
      }
    }

  return (
      <DefaultLayout>
        <div className={`p-5 mb-4 rounded-3 ${nightMode ? 'bg-dark' : ''}`}>
          <h1 onClick={rainbowMode} className="pb-2" style={{fontFamily: 'Stratum2', fontWeight: 700, fontSize: '44px'}}>{ import.meta.env.MODE === 'production' ? import.meta.env.VITE_APP_NAME : `${import.meta.env.VITE_APP_NAME} DEV` }</h1>
          <p className="lead">Hello, <b> { name }</b>. Welcome to the { import.meta.env.MODE === 'production' ? import.meta.env.VITE_APP_NAME : `${import.meta.env.VITE_APP_NAME} DEV` } homepage!</p>
          <div className="row row-cols-2">
            { cardLoader(data.user) }
          </div>
          <Cards.RecentToolsCard />
          <Cards.FavoriteToolsCard />
        </div>
      </DefaultLayout>
  )
}

export default Home;
