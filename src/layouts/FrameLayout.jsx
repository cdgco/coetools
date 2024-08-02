import { Footer, NavigationBar } from '@/components';

function FrameLayout({ children, url }) {
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
