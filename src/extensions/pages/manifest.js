/* 
 * Each page object should have a title, description, link, and component.
 *   - id: Unique identifier for the page.
 *   - title: Title of page displayed in tool directory / navigation bar.
 *   - description: Description of the page displayed in the tool directory.
 *   - category: Optional property to group pages in the tool directory
 *   - link: Unique string that will be used to identify the page in the URL.
 *   - protected: Optional property to restrict access to the page to authenticated users.
 *   - staffOnly: Optional property to restrict access to the page to staff users.
 *   - display: Optional property to control where the page is displayed in the navigation bar.
 *   - component: JSX React component that will be rendered on the page.
 *      Component must be imported using dynamic import().
 * 
 * All file imports should use the @ alias to ensure that the correct path is used.
 */

export const extensionPages = [
  // {
  //   id: 'example',
  //   title: 'Example Page',
  //   description: 'Example page description',
  //   category: 'Example Category',
  //   link: 'example',
  //   protected: true,
  //   staffOnly: false,
  //   display: '1',
  //   component: import('@/extensions/pages/ExamplePage.jsx'),
  // },
  // {
  //   id: 'example-weather',
  //   title: 'Weather',
  //   description: 'View the current weather and forecast.',
  //   category: 'Other',
  //   link: 'weather',
  //   protected: true,
  //   staffOnly: false,
  //   display: '0',
  //   component: import('@/extensions/pages/ExampleWeatherPage.jsx'),
  // },
]
