import { useContext } from 'react';
import { Table, Button } from 'react-bootstrap';
import { DataContext } from '@/DataContext'; // Adjust the import path as necessary
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faExternalLinkAlt, faHeartCirclePlus, faPencil } from '@fortawesome/free-solid-svg-icons'
import { updateRecent } from '@/utils';

const FavoriteToolsCard = () => {
  const { data, setData, nightMode } = useContext(DataContext);
  const { favorites, recents } = data.user;
  const { tools } = data.tools;
  const { direct_links } = data.user;

  // Helper function to find tool by ID
  const findToolById = (id) => tools.find(tool => tool.id === id);

  const clickHandler = (id) => {
    let newRecents = updateRecent(recents, id);
    setData({
      ...data,
      user: {
        ...data.user,
        recents: newRecents
      }
    });
  }

  let filteredFavorites = favorites.filter(favorite => tools.find(tool => tool.id === favorite));

  return (
    <>
      <h4 className="pb-2 pt-3">Favorite Tools</h4>
      {filteredFavorites.length ? 
      <Table hover size="sm" variant={nightMode ? 'dark' : ''}>
        <thead className={nightMode ? 'thead-light' : ''}>
          <tr>
            <th style={{'width': '80%'}}>Tool</th>
            <th style={{'width': '20%'}}>Action</th>
          </tr>
        </thead>
        <tbody>
          {filteredFavorites.map(favorite => {
            const curTool = findToolById(favorite);
            if (!curTool) return null; // Skip if tool not found
            
            return (
              <tr key={curTool.id}>
                <td className="align-middle">{curTool.tool_name}</td>
                <td>
                  <a href={curTool.tab === '2' && direct_links !== '1' ? `#frame?frameID=${curTool.id}` : curTool.link}
                      onClick={() => clickHandler(curTool.id)}
                      target={curTool.tab === '1' ? '_blank' : ''}
                  >
                    <Button variant="outline-primary">
                      <FontAwesomeIcon icon={faExternalLinkAlt} />
                    </Button>
                  </a>
                </td>
              </tr>
            );
          })}
        </tbody>
      </Table> :
      <p className="lead">It looks like you don&apos;t have any favorite tools. To add favorites, enter editing mode using the <span className="badge bg-secondary ms-1 me-1"><FontAwesomeIcon icon={faPencil} size="sm" /></span> button in the footer, right click on a tool in the toolbar, and select 
      <span className="badge bg-secondary ms-2 me-1"><FontAwesomeIcon icon={faHeartCirclePlus} size="sm" /> Favorite</span>.</p>}
    </>
  );
};

export default FavoriteToolsCard;
