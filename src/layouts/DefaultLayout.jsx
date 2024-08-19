import { useContext, useEffect } from 'react';
import { Footer, NavigationBar } from '@/components';
import { Container } from 'react-bootstrap';
import { DataContext } from '@/DataContext';

function DefaultLayout({ children, title }) {
  const { nightMode } = useContext(DataContext);

  useEffect(() => {
    if (title) {
      document.title = `${import.meta.env.VITE_APP_NAME} - ${title}`;
    }
  }, [title]);

  return (
    <div className={nightMode ? 'bg-dark text-white' : ''}>
      <div>
        <NavigationBar />
        <Container style={{marginTop: '56px', minHeight: 'calc(100vh - 116px)'}} role="main">
          {children}
        </Container>
        <Footer />
      </div>
    </div>
  );
}

export default DefaultLayout;
