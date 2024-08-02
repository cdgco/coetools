import { useState, useEffect } from 'react';
import { Form, Col, InputGroup } from 'react-bootstrap';

// The page component accepts props like getUserData, setUserData, and manifest
// Use these functions every time a setting changes, they will be saved when the user clicks the save button
const ExampleSettings = ({ getUserData, setUserData, manifest }) => {
  // To fetch user data, use getUserData passing the extension ID
  const [settings, setSettings] = useState(getUserData(manifest.id));

  useEffect(() => {
    // To update user data, use setUserData passing the extension ID and data
    setUserData(manifest.id, settings);
  }, [settings, manifest.id, setUserData]);

  // You can use a number of pre-installed libraries including:
  // - react-bootstrap
  // - primereact
  // - @tanstack/react-query
  // - react-toastify
  // - @fortawesome/react-fontawesome
  return (
    <Form.Group as={Col}>
      <InputGroup className="mb-3">
        <Form.Control
          type="text"
          placeholder="Example Input"
          value={settings.input}
          onChange={(e) => setSettings({ ...settings, input: e.target.value })}
        />
      </InputGroup>
    </Form.Group>
  )
}

// Use a default export for the settings
export default ExampleSettings;
