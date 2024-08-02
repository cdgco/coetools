import { useContext, useEffect } from 'react';
import { Form, Button, Modal } from 'react-bootstrap';
import { v4 as uuidv4 } from 'uuid';
import { DataContext } from '@/DataContext';

function ToolModal({ tool, setTool, showModal, setShowModal, onSubmit, mode }) {
  const { data } = useContext(DataContext);

  useEffect(() => {
    if (mode === 'add') {
      setTool({
        id: uuidv4(),
        tool_name: '',
        tool_description: '',
        category: '',
        link: '',
        tab: '1',
        staff_only: '0',
        display: '0'
      });
    }
  }, [mode, setTool, showModal]);

  const onFormSubmit = (e) => {
    e.preventDefault();
    onSubmit(mode, tool);
  }

  return (
    <Modal backdrop={true} show={showModal} size="lg" onHide={() => setShowModal(false)}>
      <Modal.Header closeButton>
        <Modal.Title>{mode === 'edit' ? 'Edit Tool' : 'Add Tool'}</Modal.Title>
      </Modal.Header>

      <Modal.Body>
        <Form onSubmit={onFormSubmit} id="toolForm">
          <Form.Group className="mb-3" controlId="formName">
            <Form.Label>Name</Form.Label>
            <Form.Control type="text" value={tool ? tool.tool_name : ''} onChange={(e) => setTool({ ...tool, tool_name: e.target.value })} required />
          </Form.Group>
          <Form.Group className="mb-3" controlId="formDescription">
            <Form.Label>Description</Form.Label>
            <Form.Control as="textarea" value={tool ? tool.tool_description : ''} onChange={(e) => setTool({ ...tool, tool_description: e.target.value })} required />
          </Form.Group>
          <Form.Group className="mb-3" controlId="formCategory">
            <Form.Label>Category</Form.Label>
            <Form.Control type="text" value={tool ? tool.category : ''} onChange={(e) => setTool({ ...tool, category: e.target.value })} required list='categories' />
            <datalist id="categories">
              { data.tools.categories.map((category, index) => <option key={index} value={category} />) }
            </datalist>
          </Form.Group>
          <Form.Group className="mb-3" controlId="formLink">
            <Form.Label>URL</Form.Label>
            <Form.Control type="text" placeholder="https://link.com/page.php" value={tool ? tool.link : ''} onChange={(e) => setTool({ ...tool, link: e.target.value })} required />
          </Form.Group>
          <Form.Group className="mb-3" controlId="formTab">
            <Form.Label>Open Link</Form.Label>
            <Form.Select aria-label="Open Link" value={tool ? tool.tab : ''} onChange={(e) => setTool({ ...tool, tab: e.target.value })}>
              <option value="1">New Tab</option>
              <option value="0">Same Tab</option>
              <option value="2">Iframe</option>
            </Form.Select>
          </Form.Group>
          <Form.Group className="mb-3" controlId="formStaffOnly">
            <Form.Label>Staff Only</Form.Label>
            <Form.Select aria-label="Staff Only" value={tool ? tool.staff_only : ''} onChange={(e) => setTool({ ...tool, staff_only: e.target.value })}>
            <option value="0">No</option>
            <option value="1">Yes</option>
            </Form.Select>
          </Form.Group>
          <Form.Group className="mb-3" controlId="formDisplay">
            <Form.Label>Header Display</Form.Label>
            <Form.Select aria-label="Header Display" value={tool ? tool.display : ''} onChange={(e) => setTool({ ...tool, display: e.target.value })}>
              <option value="0">Show In List</option>
              <option value="1">Show Directly On Header</option>
              <option value="2">Hide From Header</option>
            </Form.Select>
          </Form.Group>
        </Form>
      </Modal.Body>

      <Modal.Footer>
        <Button type="submit" variant="success" form="toolForm">Save</Button>
        <Button type="button" variant="secondary" onClick={() => setShowModal(false)}>Cancel</Button>
      </Modal.Footer>
    </Modal>
  );
}

export default ToolModal;
