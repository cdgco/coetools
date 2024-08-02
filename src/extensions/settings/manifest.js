/* 
 * Each settings object should have a title, id, and component.
 *   title: Title of settings category displayed in the settings page.
 *   id: Unique string that will be used to identify the extension. This must match the ID used in the extension's manifest file.
 *   component: JSX React component that will be rendered on the settings page.
 *      Component must be imported using dynamic import().
 *   staffOnly: Optional property to restrict access to the extension to staff users.
 * 
 * All file imports should use the @ alias to ensure that the correct path is used.
 */

export const extensionSettings = [
  // {
  //   'title': 'Example Settings',
  //   'id': 'example',
  //   'staffOnly': false,
  //   'component': import('@/extensions/settings/ExampleSettings.jsx')
  // },
  // {
  //   'title': 'Example Weather Settings',
  //   'id': 'example-weather',
  //   'staffOnly': false,
  //   'component': import('@/extensions/settings/ExampleWeatherSettings.jsx')
  // },
]
