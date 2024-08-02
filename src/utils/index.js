import { toast } from "react-toastify";

async function sendRequest(url, method, body, successMessage) {
  let fullUrl = `${import.meta.env.VITE_API_URL}/${url}.php`;
  try {
    const response = await fetch(fullUrl, {
      method: method,
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(body),
    });
    const data = await response.json();
    if (!data.success) {
      toast.error(`${data.message}`);
    } else if (successMessage) {
      toast.success(`${successMessage}`);
    }
  } catch (error) {
    toast.error(`${error.message}: ${url}`);
  }
}

export function updateRecent(recents, toolId) {
  const newRecents = [
    toolId,
    ...recents.filter((recent) => recent !== toolId),
  ].slice(0, 5);
  const recentsString = newRecents.join(",");
  sendRequest("updateRecents", "POST", { recents: recentsString });

  return newRecents;
}

export function updateLinkMode(directLinkMode) {
  let int = directLinkMode ? 1 : 0;
  sendRequest("updateLinkMode", "POST", { direct_links: int });
}

export function updateNightMode(nightMode) {
  let int = nightMode ? 1 : 0;
  sendRequest("updateNightMode", "POST", { night_mode: int });
}

export function resetLayout() {
  sendRequest(
    "updateLayout",
    "POST",
    { hidden_elements: "", layout: "" },
    "Layout reset to default."
  );
}

export function addFavorite(favorites, toolId) {
  const newFavorites = [
    toolId,
    ...favorites.filter((favorite) => favorite !== toolId),
  ];
  const favoritesString = newFavorites.join(",");

  sendRequest(
    "updateFavorites",
    "POST",
    { favorites: favoritesString },
    "Tool added to favorites."
  );

  return newFavorites;
}

export function removeFavorite(favorites, toolId) {
  const newFavorites = favorites.filter((favorite) => favorite !== toolId);
  const favoritesString = newFavorites.join(",");

  sendRequest(
    "updateFavorites",
    "POST",
    { favorites: favoritesString },
    "Tool removed from favorites."
  );

  return newFavorites;
}

export function hideTools(hidden, tools) {
  const newHidden = [...new Set([...hidden, ...tools])];
  const hiddenString = newHidden.join(",");

  sendRequest("updateHidden", "POST", { hidden_elements: hiddenString }, "Tool hidden.");

  return newHidden;
}

export function unhideAll() {
  sendRequest("updateHidden", "POST", { hidden_elements: "" }, "All tools unhidden.");
  return [];
}

export function updateSettings(settings) {
  let encodedSettings = {};

  for (const [key, value] of Object.entries(settings)) {
    if (key === "name" || key === "extension_data") {
      encodedSettings[key] = value;
    } else {
      encodedSettings[key] = encodeURIComponent(JSON.stringify(value));
    }
  }

  sendRequest("updateSettings", "POST", encodedSettings, "Settings updated.");
}

export async function updateGlobalData(extensionName, extensionData) {
  await sendRequest("updateGlobalData", "POST", { extension_name: extensionName, extension_data: extensionData });
}

export function addTool(tool) {
  sendRequest("addTool", "POST", tool, "Tool added.");
}

export function removeTool(toolId) {
  sendRequest("removeTool", "POST", { id: toolId }, "Tool removed.");
}

export function updateTool(tool) {
  sendRequest("updateTool", "POST", tool, "Tool updated.");
}
