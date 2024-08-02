import { useContext } from 'react';
import { DataContext } from '@/DataContext';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faGithub } from '@fortawesome/free-brands-svg-icons';
import { faTrash, faSave, faExternalLinkAlt, faPencil, faMoon, faSun, faDesktop, faLink } from '@fortawesome/free-solid-svg-icons';
import { Row, Col, Container } from 'react-bootstrap';
import { Modals, FooterButton } from '@/components';
import { updateNightMode, updateLinkMode, resetLayout } from '@/utils';
import { toast } from 'react-toastify';

function Footer({ url }) {
  const { editMode, nightMode, data, setEditMode, setNightMode, setData, isAuthenticated } = useContext(DataContext);

  const savePreferences = () => {
    setEditMode(false);
    toast.success('Editing mode disabled. Preferences saved.');
  }

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

  const resetToolbar = () => {
    resetLayout();
    setData({
      ...data,
      user: {
        ...data.user,
        hidden_elements: [],
        layout: ""
      }
    });
  }

  return (
    <>
      <footer className={`footer fixed-bottom ${nightMode ? 'bg-dark' : ''}`}>
        <Container fluid>
          <Row className="justify-content-between">
            <Col className="col-auto me-auto">
              <span className={`ms-3 ${nightMode ? 'text-white' : 'text-muted'}`}>
                &copy; {new Date().getFullYear()} <a href="https://github.com/cdgco/coetools" target="_blank" style={{textDecoration: 'none'}}>COE Tools <FontAwesomeIcon icon={faGithub} /></a> by Carter Roeser.
              </span>
            </Col>
              { isAuthenticated &&
                <Col id="editMode" className={`col-auto ${nightMode ? 'dark-edit' : ''}`}>
                  { url && <FooterButton title="Open In New Tab" link={url} icon={faExternalLinkAlt} /> }
                  { !editMode && <FooterButton title="Edit Preferences" icon={faPencil} onClick={() => setEditMode(!editMode)} /> }
                  { editMode &&
                    <>
                      <FooterButton title={`Turn on ${nightMode ? 'Light' : 'Dark'} Mode`} icon={nightMode ? faSun : faMoon} onClick={toggleNightMode} />
                      <FooterButton title={`Turn on ${data.user.direct_links ? 'Iframe' : 'Direct Link'} Mode`} icon={data.user.direct_links ? faDesktop : faLink} onClick={toggleDirectLinks} />
                      <FooterButton title="Reset Toolbar" icon={faTrash} onClick={resetToolbar} />
                      <FooterButton title="Save Preferences" icon={faSave} onClick={savePreferences} />
                    </>
                  }
                </Col>
              }
          </Row>
        </Container>
    </footer>
    { editMode && <Modals.EditModal /> }
  </>
  );
}

export default Footer;
