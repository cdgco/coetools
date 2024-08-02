import { useContext } from 'react';
import { Footer, NavigationBar } from '@/components';
import { Container } from 'react-bootstrap';
import { DataContext } from '@/DataContext';

function DefaultLayout({ children }) {
  const { nightMode } = useContext(DataContext);

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
