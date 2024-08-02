import { useContext } from 'react';
import { Table, Button } from 'react-bootstrap';
import { DataContext } from '@/DataContext'; // Adjust the import path as necessary
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faExternalLinkAlt } from '@fortawesome/free-solid-svg-icons'
import { updateRecent } from '@/utils';

const RecentToolsCard = () => {
  const { data, setData, nightMode } = useContext(DataContext);
  const { recents, direct_links } = data.user;
  const { tools } = data.tools;

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

  let filteredRecents = recents.filter(recent => tools.find(tool => tool.id === recent));

  return (
    <>
      <h4 className="pb-2 pt-3">Recent Tools</h4>
      { filteredRecents.length ?
      <Table hover size="sm" variant={nightMode ? 'dark' : ''}>
        <thead className={nightMode ? 'thead-light' : ''}>
          <tr>
            <th style={{'width': '80%'}}>Tool</th>
            <th style={{'width': '20%'}}>Action</th>
          </tr>
        </thead>
        <tbody>
          {filteredRecents.map(recent => {
            const curTool = findToolById(recent);
            if (!curTool) return null;
            
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
      <p className="lead">It looks like you don&apos;t have any recent tools. Once you open a tool it will be added to your recents.</p>}
    </>
  );
};

export default RecentToolsCard;
