import { OverlayTrigger, Tooltip } from "react-bootstrap"
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { useContext } from 'react';
import { DataContext } from '@/DataContext';

function FooterButton({ title, link, icon, onClick = () => { } }) {
  let { nightMode } = useContext(DataContext);

  if (link) {
    return (
      <OverlayTrigger overlay={<Tooltip>{title}</Tooltip>}>
        <a href={link} title={title} className={`pull-right me-3 linkButton ${nightMode ? 'nightMode' : ''}`} 
        target="_blank" onClick={onClick}>
          <FontAwesomeIcon icon={icon} />
        </a>
      </OverlayTrigger>
    )
  } else {
    return (
      <OverlayTrigger overlay={<Tooltip>{title}</Tooltip>}>
        <button title={title} className={`pull-right me-3 linkButton ${nightMode ? 'nightMode' : ''}`} 
        onClick={onClick}>
          <FontAwesomeIcon icon={icon} />
        </button>
      </OverlayTrigger>
    )
  }
}

export default FooterButton;
