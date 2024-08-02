/* 
 * Each card object should have a title, id, and component.
 *   title: String that will be displayed on the settings page to enable or disable the card. 
 *   id: Unique string that will be used to identify the card.
 *   component: JSX React component that will be rendered on the card.
 *      Component must be imported using dynamic import().
 *   forceEnable: Optional property to force the card to be enabled, hiding the toggle on the settings page.
 *   staffOnly: Optional property to restrict access to the card to staff users.
 * 
 * Cards are displayed in the order they are defined in the array.
 * 
 * All file imports should use the @ alias to ensure that the correct path is used.
 */

export const extensionCards = [
  // {
  //   title: 'Example Card',
  //   id: 'example',
  //   forceEnable: true,
  //   staffOnly: false,
  //   component: import('@/extensions/cards/ExampleCard.jsx'),
  // },
  // {
  //   title: 'Shared Task Tracker',
  //   id: 'example-task-tracker',
  //   forceEnable: false,
  //   staffOnly: false,
  //   component: import('@/extensions/cards/ExampleTaskTrackerCard.jsx'),
  // },
  // {
  //   title: 'Weather',
  //   id: 'example-weather',
  //   forceEnable: false,
  //   staffOnly: false,
  //   component: import('@/extensions/cards/ExampleWeatherCard.jsx'),
  // },
  {
    title: 'Recent Announcements',
    id: 'announcements',
    forceEnable: true,
    staffOnly: false,
    component: import('@/extensions/cards/AnnouncementCard.jsx'),
  },
]
