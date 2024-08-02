import { useEffect, useContext } from 'react';
import { useNavigate } from 'react-router-dom';
import { DataContext } from '@/DataContext';

const LogoutHandler = () => {
  const { setIsAuthenticated, loggingOut, setLoggingOut } = useContext(DataContext);
  const navigate = useNavigate();

    useEffect(() => {
      const logout = async () => {
        window.location.href = import.meta.env.VITE_API_URL + '/auth/logout.php?redirect=' + encodeURIComponent(window.location.href);
    } 

    if (loggingOut) {
      logout();
    }
  }, [loggingOut, navigate, setIsAuthenticated, setLoggingOut]);
};

export default LogoutHandler;
