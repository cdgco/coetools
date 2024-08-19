import { lazy, Suspense } from "react";
import { ProtectedRoute } from "@/components";
import { extensionPages } from "./pages/manifest";
import { extensionCards } from "./cards/manifest";
import { extensionSettings } from "./settings/manifest";
import { Row } from 'react-bootstrap';
import { updateGlobalData, updateSettings } from "@/utils";
import { LoadingPage } from "@/pages";
import { DefaultLayout } from "@/layouts";

////////////////////////////////////////
// Validation for Extension Manifests //
////////////////////////////////////////

const validateExtensionPages = () => {
  return extensionPages.filter(page => {
    // Check for required fields
    if (!page.title || !page.description || !page.link || !page.component || !page.id) {
      console.error('Missing required fields for extension page:', page);
      return false;
    }

    if (!page.display) {
      page.display = '2';
    }

    // Validate types
    if (typeof page.title !== 'string' || typeof page.description !== 'string' ||typeof page.link !== 'string' || typeof page.id !== 'string') {
      console.error('Invalid property type for extension page:', page);
      return false;
    }

    if (typeof page.component !== 'object') {
      console.error('Invalid component type for extension page:', page);
      return false;
    }

    if (page.display && page.display != '1' && page.display != '2' && page.display != '0') {
      console.error('Invalid display type for extension page:', page);
      return false;
    }

    // Check for duplicate links
    if (extensionPages.filter(p => p.link === page.link).length > 1) {
      console.error('Duplicate link for extension page:', page);
      return false;
    }

    // Check for duplicate IDs
    if (extensionPages.filter(p => p.id === page.id).length > 1) {
      console.error('Duplicate ID for extension page:', page);
      return false;
    }

    return true;
  });
}

const validateExtensionCards = () => {
  return extensionCards.filter(card => {
    // Check for required fields
    if (!card.title || !card.id || !card.component) {
      console.error('Missing required fields for extension card:', card);
      return false;
    }

    // Validate types
    if (typeof card.title !== 'string' || typeof card.id !== 'string') {
      console.error('Invalid type for extension card:', card);
      return false;
    }

    if (typeof card.component !== 'object') {
      console.error('Invalid component type for extension card:', card);
      return false;
    }

    // Check for duplicate IDs
    if (extensionCards.filter(c => c.id === card.id).length > 1) {
      console.error('Duplicate ID for extension card:', card);
      return false;
    }

    return true;
  });
}

const validateExtensionSettings = () => {
  return extensionSettings.filter(setting => {
    // Check for required fields
    if (!setting.title || !setting.id || !setting.component) {
      console.error('Missing required fields for extension setting:', setting);
      return false;
    }

    // Validate types
    if (typeof setting.title !== 'string' || typeof setting.id !== 'string') {
      console.error('Invalid type for extension setting:', setting);
      return false;
    }

    if (typeof setting.component !== 'object') {
      console.error('Invalid component type for extension setting:', setting);
      return false;
    }

    // Check for duplicate IDs
    if (extensionSettings.filter(s => s.id === setting.id).length > 1) {
      console.error('Duplicate ID for extension setting:', setting);
      return false;
    }

    return true;
  });
}

//////////////////////////////////////////////
// Dynamic Loaders for importing extensions //
//////////////////////////////////////////////

const cardLoader = (user) => {
  let loadedCards = [];
  let statusCards = user?.status_cards || [];

  let isStaff = user?.staff || false;

  let validatedCards = validateExtensionCards();

  validatedCards.forEach(card => {
    let foundCard = statusCards.find(c => c === card.id);

    if ((foundCard || card.forceEnable) && (!card.staffOnly || isStaff)) {
      let LoadedCard = lazy(() => card.component);

      loadedCards.push(
        <Suspense fallback={<div>Loading Extension Card...</div>} key={card.id}>
          <LoadedCard setUserData={setUserData} getUserData={getUserData} setGlobalData={setGlobalData} getGlobalData={getGlobalData} manifest={card} />
        </Suspense>
      );
    }
  })

  return loadedCards
}

const settingsLoader = (user, updateSettings, retrieveSettings) => {
  let loadedSettings = [];

  const validatedSettings = validateExtensionSettings();

  let isStaff = user?.staff || false;

  validatedSettings.forEach(setting => {
    let staffOnlyCard = setting.staffOnly;

    let foundCard = extensionCards.find(c => c.id === setting.id && c.forceEnable);
    let foundSetting = user?.status_cards.find(c => c === setting.id && (!staffOnlyCard || isStaff));
    let pageSettings = extensionPages.find(p => (!p.staffOnly || isStaff));
    
    if (foundSetting || pageSettings || foundCard) {
      let LoadedSetting = lazy(() => setting.component);

      loadedSettings.push(
        <Suspense fallback={<div>Loading Extension Settings...</div>} key={setting.id}>
          <h3 className="mb-3">{setting.title}</h3>
          <Row className="mb-3">
            <LoadedSetting setUserData={updateSettings} getUserData={retrieveSettings} manifest={setting} />
          </Row>
        </Suspense>
      );
    }
  })

  return loadedSettings;
}

const directoryPageLoader = (user) => {
  return extensionPages.filter(page => (!page.staffOnly || user.staff)).map(page => {
    return {
      id: page.id,
      tool_name: page.title,
      tool_description: page.description,
      category: page.category,
      link: import.meta.env.VITE_FRONTEND_URL + '/#' + page.link,
      staff_only: page.staffOnly ? 1 : 0,
      display: page.display,
      tab: 0,
    }
  });
};

const routerPageLoader = () => {
  const routes = validateExtensionPages()
  
  return routes.map((page) => {
    let LoadedComponent = lazy(() => page.component);
  
    return {
      path: page.link,
      element: (
        <Suspense fallback={<LoadingPage />}>
          {
            page.protected 
              ? <ProtectedRoute><DefaultLayout title={page.title}><LoadedComponent setUserData={setUserData} getUserData={getUserData} setGlobalData={setGlobalData} getGlobalData={getGlobalData} manifest={page} /></DefaultLayout></ProtectedRoute> 
              : <DefaultLayout title={page.title}><LoadedComponent setUserData={setUserData} getUserData={getUserData} setGlobalData={setGlobalData} getGlobalData={getGlobalData} manifest={page} /></DefaultLayout>
          }
        </Suspense>
      )
    }
  });
}

////////////////////////////////////////
// Public APIs for Extension Database //
////////////////////////////////////////

/**
 * Retrieves global data for an extension
 * @param {string} extensionID
 * @param {React.Context} context
 * @returns {object}
 */
const getGlobalData = (extensionID, context) => {
  if (!extensionID) {
    throw new Error('Extension ID is required.');
  }

  if (!context) {
    throw new Error('Context is required.');
  }

  const { data } = context;
  if (!data.global_data || !data.global_data[extensionID]) {
    return {};
  } else {
    if (typeof data.global_data[extensionID] === 'object') {
      return data.global_data[extensionID];
    } else {
      return JSON.parse(data.global_data[extensionID]);
    }
  }
}

/**
 * Updates global data for an extension
 * @param {string} extensionID 
 * @param {object} extensionData
 * @async
 */
const setGlobalData = async (extensionID, extensionData) => {
  if (!extensionID) {
    throw new Error('Extension ID is required.');
  }

  if (!extensionData || typeof extensionData !== 'object') {
    throw new Error('Extension data must be an object.');
  }

  await updateGlobalData(extensionID, extensionData);
}

/**
 * Retrieves user data for an extension
 * @param {string} extensionID
 * @param {React.Context} context 
 * @returns {object}
 */
const getUserData = (extensionID, context) => {
  if (!extensionID) {
    throw new Error('Extension ID is required.');
  }

  if (!context) {
    throw new Error('Context is required.');
  }

  const { data } = context;
  if (!data.user.extension_data || !data.user.extension_data[extensionID]) {
    return {};
  } else {
    if (typeof data.user.extension_data[extensionID] === 'object') {
      return data.user.extension_data[extensionID];
    } else {
      return JSON.parse(data.user.extension_data[extensionID]);
    }
  }
}

/**
 * Updates user data for an extension
 * @param {string} extensionID
 * @param {object} extensionData 
 * @param {React.Context} context 
 */
const setUserData = async (extensionID, extensionData, context) => {
  if (!extensionID) {
    throw new Error('Extension ID is required.');
  }

  if (!extensionData || typeof extensionData !== 'object') {
    throw new Error('Extension data must be an object.');
  }

  if (!context) {
    throw new Error('Context is required.');
  }

  const { data, setData } = context;

  let settings = {
    name: data.user.name,
    status_cards: data.user.status_cards,
    extension_data: {
      ...data.user.extension_data,
      [extensionID]: extensionData
    }
  }

  await updateSettings(settings);
  setData({
    ...data,
    user: {
      ...data.user,
      extension_data: {
        ...data.user.extension_data,
        [extensionID]: extensionData
      }
    }
  });
}

export {
  cardLoader,
  settingsLoader,
  routerPageLoader,
  directoryPageLoader,
  getGlobalData,
  setGlobalData,
  getUserData,
  setUserData,
}
