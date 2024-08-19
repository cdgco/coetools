import { useEffect } from 'react';
import { Footer, NavigationBar } from '@/components';

function FrameLayout({ children, url, title }) {

  useEffect(() => {
    if (title) {
      document.title = `${import.meta.env.VITE_APP_NAME} - ${title}`;
    }
  }, [title]);

  return (
    <div>
      <div>
        <NavigationBar />
        <div style={{marginTop: '56px', height: 'calc(100vh - 116px)'}} role="main">
          {children}
        </div>
        <Footer url={url}/>
      </div>
    </div>
  );
}

export default FrameLayout;
