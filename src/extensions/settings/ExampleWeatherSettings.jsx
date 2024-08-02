import { useState, useEffect } from 'react';
import { Form, Col, InputGroup } from 'react-bootstrap';

const ExampleWeatherSettings = ({ getUserData, setUserData, manifest }) => {
  const [preferences, setPreferences] = useState(getUserData(manifest.id));

  useEffect(() => {
    setUserData(manifest.id, preferences);
  }, [preferences, manifest.id, setUserData]);

  return (
    <Form.Group as={Col}>
      <InputGroup className="mb-3">
        <Form.Control
          type="text"
          placeholder="Zip Code"
          value={preferences.zipcode || ''}
          onChange={(e) => setPreferences({ ...preferences, zipcode: e.target.value })}
        />
      </InputGroup>
      <InputGroup>
        <Form.Select
          value={preferences.unit}
          onChange={(e) => setPreferences({ ...preferences, unit: e.target.value })}
        >
          <option value="">Celsius</option>
          <option value="fahrenheit">Fahrenheit</option>
        </Form.Select>
      </InputGroup>
    </Form.Group>
  )
}

export default ExampleWeatherSettings;
