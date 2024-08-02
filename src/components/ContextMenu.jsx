import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import * as RadixMenu from '@radix-ui/react-context-menu';
import { faEye, faEyeSlash, faHeartCircleMinus, faHeartCirclePlus } from '@fortawesome/free-solid-svg-icons';
import { addFavorite, removeFavorite, hideTools, unhideAll } from '@/utils';
import { DataContext } from '@/DataContext';
import { useContext } from 'react';

function ContextMenu({ type, favorite, children, id }) {
  const { data, setData } = useContext(DataContext);

  const updateFavorite = (action) => {
    let newFavorites = action === 'add' ? addFavorite(data.user.favorites, id) : removeFavorite(data.user.favorites, id);

    setData({
      ...data,
      user: {
        ...data.user,
        favorites: newFavorites
      }
    });
  }

  const updateHidden = (action) => {
    // If target is tool, hide it. If target is category, hide all tools in that category
    let hiddenTools = type === 'tool' ? [id] : data.tools.tools.filter(tool => tool.category === id).map(tool => tool.id);
    let newHidden = action === 'hide' ? hideTools(data.user.hidden_elements, hiddenTools) : unhideAll();
    
    setData({
      ...data,
      user: {
        ...data.user,
        hidden_elements: newHidden
      }
    });
  }

  const favoriteButton = () => <RadixMenu.Item onClick={() => updateFavorite('add')} className="ContextMenuItem"><FontAwesomeIcon icon={faHeartCirclePlus} className="me-2" /> Favorite</RadixMenu.Item>;
  const unfavoriteButton = () => <RadixMenu.Item onClick={() => updateFavorite('remove')} className="ContextMenuItem"><FontAwesomeIcon icon={faHeartCircleMinus} className="me-2" /> Remove Favorite</RadixMenu.Item>;
  const hideButton = () => <RadixMenu.Item onClick={() => updateHidden('hide')} className="ContextMenuItem"><FontAwesomeIcon icon={faEyeSlash} className="me-2" /> Hide</RadixMenu.Item>;
  const unhideButton = () => <RadixMenu.Item onClick={() => updateHidden('unhide')} className="ContextMenuItem"><FontAwesomeIcon icon={faEye} className="me-2" /> Unhide All</RadixMenu.Item>;

  const menuContent = () => {
    switch(type) {
      case 'tool':
        return (
          <>
            { hideButton() }
            { favorite ? unfavoriteButton() : favoriteButton() }
          </>
        )
      case 'category':
        return hideButton();
      case 'unhide':
        return unhideButton();
      case 'favorite':
        return favoriteButton();
      case 'unfavorite':
        return unfavoriteButton();
    }
  }

  return (
    <RadixMenu.Root>
      <RadixMenu.Trigger>{children}</RadixMenu.Trigger>
      <RadixMenu.Portal>
        <RadixMenu.Content className="ContextMenuContent" sideOffset={5} align="end">
          { menuContent() }
        </RadixMenu.Content>
      </RadixMenu.Portal>
    </RadixMenu.Root>
  );
}

export default ContextMenu;
