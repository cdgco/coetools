import { useContext, useState, useEffect } from 'react';
import { Navbar, Nav, NavDropdown, Tooltip, OverlayTrigger, Offcanvas, Container } from 'react-bootstrap';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faScrewdriverWrench, faSearch } from '@fortawesome/free-solid-svg-icons';
import { ContextMenu, Modals } from './'
import { DataContext } from '@/DataContext';
import { updateRecent } from '@/utils';
import { faSignOutAlt } from '@fortawesome/free-solid-svg-icons';
import { extensionPages } from '@/extensions/pages/manifest';

const NavigationBar = () => {
  const { editMode, data, setData, nightMode, isAuthenticated, setLoggingOut } = useContext(DataContext);
  const [showSearch, setShowSearch] = useState(false);
  let combinedItems = [], HiddenElements = [], Favorites = [], Tools = [], Recents = [], DirectLinks = false;

  const clickHandler = (id) => {
    let newRecents = updateRecent(Recents, id);
    setData({
      ...data,
      user: {
        ...data.user,
        recents: newRecents
      }
    });
  }

  useEffect(() => {
    const handleKeyDown = (event) => {
      // Check if Ctrl or Cmd on Mac, Shift, and 'F' are pressed together
      if ((event.ctrlKey || event.metaKey) && event.shiftKey && event.key.toLowerCase() === 'f') {
        event.preventDefault(); // Prevent the default action to avoid conflicts
        setShowSearch(true); // Open the search modal
      }
    };

    // Add event listener when the component mounts
    window.addEventListener('keydown', handleKeyDown);

    // Remove event listener on cleanup
    return () => {
      window.removeEventListener('keydown', handleKeyDown);
    };
  }, []);

  if (data != null && data.user != null) {
    const { direct_links, hidden_elements, favorites, recents } = data.user;
    let { tools, categories } = data.tools;

    HiddenElements = hidden_elements;
    Favorites = favorites;
    DirectLinks = direct_links;
    Tools = tools;
    Recents = recents;

    // Find categories from extension pages and combine with existing categories
    extensionPages.forEach(page => {
      if (page.category && !categories.includes(page.category)) {
        categories.push(page.category);
      }

      if (!tools.find(tool => tool?.id === page.id) && (!page.staffOnly || data.user.staff)) {
        tools.push({
          id: page.id,
          tool_name: page.title,
          tool_description: page.description,
          category: page.category,
          link: import.meta.env.VITE_FRONTEND_URL + '/#' + page.link,
          staff_only: page.staffOnly ? 1 : 0,
          display: page.display,
          tab: 0,
        });
      }
    });

    combinedItems = [
      ...tools.filter(tool => tool.display === '1').map(tool => ({
        name: tool.tool_name,
        type: 'link',
        tool
      })),
      ...categories.map(category => ({
        name: category,
        type: 'dropdown',
        category
      }))
    ].sort((a, b) => a.name.localeCompare(b.name));
  }

  const renderDirectLink = (tool) => {
    // If the tool is hidden, don't render it
    if (HiddenElements.includes(tool.id)) return null;
    else if (editMode) {
      return (
        <ContextMenu key={tool.id} favorite={Favorites.includes(tool.id)} type="tool" id={tool.id}>
          <Nav.Link 
          href={tool.tab === '2' && !DirectLinks ? `#frame?frameID=${tool.id}` : tool.link} 
          target={tool.tab === '1' ? '_blank' : ''} className={Favorites.includes(tool.id) ? 'favorite' : ''}
          onClick={() => clickHandler(tool.id)} >
            {tool.tool_name}
          </Nav.Link>
        </ContextMenu>
      ) 
    } else {
      return (
        <Nav.Link key={tool.id}
          href={tool.tab === '2' && !DirectLinks ? `#frame?frameID=${tool.id}` : tool.link} 
          target={tool.tab === '1' ? '_blank' : ''} className={Favorites.includes(tool.id) ? 'favorite' : ''}
          onClick={() => clickHandler(tool.id)} >
            {tool.tool_name}
        </Nav.Link>
      )
    }
  };

  // Render function for a dropdown category
  const renderDropdown = (category) => {
    const categoryTools = Tools.filter(tool => tool.category === category && tool.display !== '1' && tool.display !== '2')
      .sort((a, b) => a.tool_name.localeCompare(b.tool_name));

    // If all tools in the category are hidden, don't render the category
    if (categoryTools.every(tool => HiddenElements.includes(tool.id))) return null;

    // Don't render empty categories
    if (categoryTools.length === 0) return null;

    if (editMode) {
      return (
        <ContextMenu key={category} type="category" id={category}>
          <NavDropdown title={category} id={`dropdown-${category.replace(/\s+/g, '-').toLowerCase()}`}>
          {categoryTools.map(tool => {
            if (HiddenElements.includes(tool.id)) return null;

            return (
              <ContextMenu key={tool.id} favorite={Favorites.includes(tool.id)} type="tool" id={tool.id}>
                <NavDropdown.Item href={tool.tab === '2' && !DirectLinks ? `#frame?frameID=${tool.id}` : tool.link} 
                target={tool.tab === '1' ? '_blank' : ''} 
                className={Favorites.includes(tool.id) ? 'favorite' : ''}
                onClick={() => clickHandler(tool.id)} >
                  {tool.tool_name}
                </NavDropdown.Item>
              </ContextMenu>
            );
          })}
        </NavDropdown>
      </ContextMenu>
      )
    } else {
      return (
        <NavDropdown title={category} id={`dropdown-${category.replace(/\s+/g, '-').toLowerCase()}`} key={category}>
          {categoryTools.map(tool => {
            if (HiddenElements.includes(tool.id)) return null;

            return (
              <NavDropdown.Item href={tool.tab === '2' && !DirectLinks ? `#frame?frameID=${tool.id}` : tool.link} 
              key={tool.id} target={tool.tab === '1' ? '_blank' : ''} 
              className={Favorites.includes(tool.id) ? 'favorite' : ''}
              onClick={() => clickHandler(tool.id)} >
                {tool.tool_name}
              </NavDropdown.Item>
            );
          })}
        </NavDropdown>
      )
    }
  };

  const handleLogout = async () => {
    setLoggingOut(true);
  }

  let nav = (
      <Navbar className="bg-body-tertiary navbar-dark" expand="xxl" bg="dark" variant="dark" fixed="top" id="navx" style={{'padding': '0.5rem 1rem'}} >
        <Container fluid>
        { isAuthenticated && <Navbar.Toggle aria-controls="offcanvas-navbar-nav" /> }
          <Navbar.Brand href="./">{ import.meta.env.MODE === 'production' ? import.meta.env.VITE_APP_NAME : `${import.meta.env.VITE_APP_NAME} DEV` }</Navbar.Brand>
          { isAuthenticated && 
            <>
              <Navbar.Offcanvas
                id={`offcanvas-navbar-nav`}
                aria-labelledby={`offcanvas-navbar-nav-label`}
                placement="start"
                className={nightMode ? 'text-bg-dark' : ''}
              >
                <Offcanvas.Header closeButton closeVariant={nightMode ? 'white' : undefined}>
                  <Offcanvas.Title id={`offcanvas-navbar-nav-label`}>
                  { import.meta.env.MODE === 'production' ? import.meta.env.VITE_APP_NAME : `${import.meta.env.VITE_APP_NAME} DEV` }
                  </Offcanvas.Title>
                </Offcanvas.Header>
                <Offcanvas.Body>
                <Nav className="me-auto">
                  {combinedItems.map(item => 
                      item.type === 'link' ? renderDirectLink(item.tool) : renderDropdown(item.name)
                    )}
                    <Nav.Link href="#directory">Tool Directory</Nav.Link>
                  </Nav>
                </Offcanvas.Body>
              </Navbar.Offcanvas>
              <Nav className="d-flex flex-row gap-2">
                <OverlayTrigger overlay={<Tooltip>Account Settings</Tooltip>} placement='bottom'>
                  <Nav.Link href="#settings">
                    <FontAwesomeIcon icon={faScrewdriverWrench} />
                  </Nav.Link>
                </OverlayTrigger>
                <OverlayTrigger overlay={<Tooltip>Tool Search</Tooltip>} placement='bottom'>
                  <button onClick={() => setShowSearch(true)} className="btn text-white">
                    <FontAwesomeIcon icon={faSearch} />
                  </button>
                </OverlayTrigger>
                <OverlayTrigger overlay={<Tooltip>Logout</Tooltip>} placement='bottom'>
                  <Nav.Link onClick={handleLogout}>
                    <FontAwesomeIcon icon={faSignOutAlt} />
                  </Nav.Link>
                </OverlayTrigger>
              </Nav>
            </>
          }
        </Container>
      </Navbar>
  )

  return (
    <>
      <Modals.SearchModal show={showSearch} setShowModal={setShowSearch} />
      { editMode ? <ContextMenu type="unhide">{nav}</ContextMenu> : nav }
    </>
  )
};

export default NavigationBar;
