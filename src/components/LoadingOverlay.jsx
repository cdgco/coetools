import loaderGif from '@/assets/img/loader.gif';
import { useEffect, useRef } from 'react';

const LoadingOverlay = ({ visible = true }) => {
  const loadingRef = useRef();

  useEffect(() => {
    // Fade in and out with opacity, once faded out, set display to none
    if (!visible) {
      loadingRef.current.style.opacity = 0;
      setTimeout(() => {
        loadingRef.current.style.display = 'none';
      }, 500);
    } else {
      loadingRef.current.style.display = 'flex';
      loadingRef.current.style.opacity = 0.7;
    }
  }, [visible]);

  return (
    <div id="preloader" ref={loadingRef}>
      <img src={loaderGif} alt="Loading..." id="loaderGIF" />
    </div>
  );
}

export default LoadingOverlay;
