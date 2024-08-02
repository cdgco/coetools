import { useContext, useState, useRef } from 'react';
import { DefaultLayout } from '@/layouts';
import { Col, Form, Row } from 'react-bootstrap';
import { DataContext } from '@/DataContext';
import { updateNightMode, updateLinkMode, updateSettings } from '@/utils';
import { extensionCards } from '@/extensions/cards/manifest';
import { settingsLoader } from '@/extensions';

function Settings() {
  const { nightMode, data, setNightMode, setData } = useContext(DataContext);
  const extensionDataRef = useRef(data.user.extension_data);
  const [name, setName] = useState(data.user.name);
  const [selectedExtensions, setSelectedExtensions] = useState(data.user.status_cards);
  const mac = navigator.userAgent.indexOf('Mac') !== -1;

  const toggleNightMode = () => {
    updateNightMode(!nightMode);
    setNightMode(!nightMode);
  }

  const toggleDirectLinks = () => {
    updateLinkMode(!data.user.direct_links);
    setData({
      ...data,
      user: {
        ...data.user,
        direct_links: !data.user.direct_links
      }
    });
  }

  const submitHandler = (e) => {
    e.preventDefault();

    updateSettings({
      name: name,
      status_cards: selectedExtensions,
      extension_data: extensionDataRef.current
    });
    setData({
      ...data,
      user: {
        ...data.user,
        name: name,
        status_cards: selectedExtensions,
        extension_data: extensionDataRef.current
      }
    });
  }

  let activeExtensions = extensionCards.filter((card) => {
      if ((!card.staffOnly || data.user.staff) && !card.forceEnable) {
        return true;
      }

      return false;
  });

  // These methods will be passed as props to the extension cards so they can interact with the extensions database
  const updateExtensionSettings = (id, value) => {
    extensionDataRef.current = {
      ...extensionDataRef.current,
      [id]: value
    };
  }

  const retrieveExtensionSettings = (id) => {
    if (!extensionDataRef.current[id]) {
      return {}
    } else {
      return extensionDataRef.current[id];
    }
  }
  
  return (
    <DefaultLayout nightMode={nightMode}>
      <div className={`p-5 mb-4 rounded-3 ${nightMode ? 'bg-dark' : ''}`}>
      <h1 className="pb-2">Account Settings</h1>
      <br/>
      <Form onSubmit={submitHandler}>
        <h3 className="mb-3">User Information</h3>
        <Row className="mb-3">
          <Form.Group as={Col} className="col-md-6" controlId="formGridName">
            <Form.Label>Name</Form.Label>
            <Form.Control type="text" placeholder="Name" value={name} onChange={(e) => setName(e.target.value)} />
          </Form.Group>

          <Form.Group as={Col} className="col-md-6" controlId="formGridUsername">
            <Form.Label>Username</Form.Label>
            <Form.Control type="text" disabled placeholder="Username" value={data.user.username} style={{cursor: 'not-allowed'}} />
          </Form.Group>
        </Row>

        <h3 className="mb-3">Preferences</h3>
        <Row className="mb-3">
          <Form.Group as={Col} className="col-md-6" controlId="formGridNightMode">
            <Form.Switch type="checkbox" label="Dark Mode" checked={nightMode} onChange={toggleNightMode} />
          </Form.Group>

          <Form.Group as={Col} className="col-md-6" controlId="formGridDirectLinks">
            <Form.Switch type="checkbox" label="Enable Direct Links" checked={data.user.direct_links} onChange={toggleDirectLinks} />
          </Form.Group>
        </Row>
        { activeExtensions.length > 0 &&
          <>
            <h3 className="mb-3">Enable Extension Cards</h3>
            <Row className="mb-3">
              <Form.Group as={Col} controlId="formGridExtensions">
                <Form.Select multiple htmlSize={6} value={selectedExtensions} onChange={(e) => setSelectedExtensions(Array.from(e.target.selectedOptions, option => option.value))}>
                  {activeExtensions.map((card, index) => <option key={index} value={card.id}>{card.title}</option>)}
                </Form.Select>
                <small className={`darkHelpBlock form-text ${nightMode ? 'text-light' : 'text-muted'}`}>
                {mac ? '⌘' : 'Ctrl'} + Click to select multiple options.&nbsp;{selectedExtensions.length > 0 && <button className="linkButton" type="button" onClick={() => setSelectedExtensions([])}>Clear Selection</button> }
                </small>
              </Form.Group>
            </Row>
          </>
        }
        { settingsLoader(data.user, updateExtensionSettings, retrieveExtensionSettings) }
        <br />
        <button type="submit" className="btn btn-primary">Save</button>
      </Form>
    </div>
    </DefaultLayout>
  )
}

export default Settings;
