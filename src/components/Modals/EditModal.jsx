import { Modal } from 'react-bootstrap';
import { useState } from 'react';
import { faTrash, faSave, faMoon, faSun, faDesktop, faLink } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

function EditModal() {
  const [showModal, setShowModal] = useState(true);

  return (
    <Modal backdrop={true} show={showModal} size="lg" onHide={() => setShowModal(false)}>
      <Modal.Header closeButton>
        <Modal.Title>User Preferences Editing Enabled</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <ul style={{ paddingLeft: '20px' }}>
          <li>Drag and drop menu items to rearrange them.</li>
          <li>Right click on menu items to hide them or add them to favorites.</li>
          <li>Right click on the &quot;Tool Directory&quot; or empty space on the navbar to unhide hidden items.</li>
          <li>Use the <FontAwesomeIcon icon={faSun} /> / <FontAwesomeIcon icon={faMoon} /> buttons in the bottom right corner to toggle light / dark mode.</li>
          <li>Use the <FontAwesomeIcon icon={faDesktop} /> / <FontAwesomeIcon icon={faLink} /> buttons in the bottom right corner to toggle direct link mode.</li>
          <li>Use the <FontAwesomeIcon icon={faTrash} /> button in the bottom right corner to reset your layout to the default.</li>
          <li>Use the <FontAwesomeIcon icon={faSave} /> button in the bottom right corner to save and exit editing mode.</li>
        </ul>
      </Modal.Body>
    </Modal>
  );
}

export default EditModal;
