import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faSearch } from '@fortawesome/free-solid-svg-icons';
import { StatusCardStatic } from '@/components/Cards';

const AnnouncementCard = () => {
  const mac = navigator.userAgent.indexOf('Mac') !== -1;
  
  return (
    <StatusCardStatic>
      Use the <FontAwesomeIcon icon={faSearch} /> button or press <span className="badge bg-secondary ms-1 me-1">{mac ? '⌘' : 'Ctrl'} + Shift + F</span> to search for tools. 
    </StatusCardStatic>
  );
};

export default AnnouncementCard;
