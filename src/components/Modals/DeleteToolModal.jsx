import { Modal, Button } from 'react-bootstrap';
import { removeTool } from '@/utils';
import { DataContext } from '@/DataContext';
import { useContext } from 'react';

function DeleteToolModal({ showModal, setShowModal, tool, setDeletedTools, deletedTools }) {
  const { data, setData } = useContext(DataContext);

  const deleteTool = () => {
    removeTool(tool.id);
    setData({
      ...data,
      tools: {
        ...data.tools,
        tools: data.tools.tools.filter(t => t.id !== tool.id)
      }
    });
    setDeletedTools([...deletedTools, tool.id]);

    setShowModal(false);
  }

  return (
    <Modal backdrop={true} show={showModal} onHide={() => setShowModal(false)} centered>
      <Modal.Header closeButton>
        <Modal.Title>Delete Tool</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <p>Are you sure you want to delete <strong>{tool ? tool.tool_name : 'this tool'}</strong>?</p>

        <p className="text-danger">This action cannot be undone.</p>
      </Modal.Body>

      <Modal.Footer>
        <Button variant="danger" onClick={deleteTool}>Delete</Button>
        <Button variant="secondary" onClick={() => setShowModal(false)}>Cancel</Button>
      </Modal.Footer>
    </Modal>
  );
}

export default DeleteToolModal;
