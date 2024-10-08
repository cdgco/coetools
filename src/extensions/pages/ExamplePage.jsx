// In order to store / retrieve data, we must use DataContext and useContext
import { useContext, useEffect } from 'react';
import { DataContext } from '@/DataContext';

// The page component can accept props like getGlobalData, setGlobalData, getUserData, setUserData, and manifest
// You can use these functions to store and retrieve data from the database
const ExamplePage = ({ getGlobalData, setGlobalData, getUserData, setUserData, manifest }) => {
  // Use the DataContext to access the extension data
  const context = useContext(DataContext);

  // If you need to style the card based on nightMode, you can access it from the context
  const { nightMode } = context;

  useEffect(() => {
    const fetchData = async () => {
      // To fetch global data, use getGlobalData passing the extension ID and context
      const globalData = await getGlobalData(manifest.id, context);

      // To fetch user data, use getUserData passing the extension ID and context
      const userData = await getUserData(manifest.id, context);
    };

    fetchData();
  }, [context, getGlobalData, getUserData, manifest.id]);

  const updateGlobalData = async (data) => {
    // To update global data, use setGlobalData passing the extension ID and data
    await setGlobalData(manifest.id, data);
  }

  const updateUserData = async (data) => {
    // To update user data, use setUserData passing the extension ID, data, and context
    await setUserData(manifest.id, data, context);
  }

  return (
    <div className={`p-5 mb-4 rounded-3 ${nightMode ? "bg-dark" : ""}`}>
      Page Content Here
      {
        // You can also use a number of pre-installed libraries including:
        // - react-bootstrap
        // - primereact
        // - @tanstack/react-query
        // - react-toastify
        // - @fortawesome/react-fontawesome
      }
    </div>
  );
};

// Use a default export for the page
export default ExamplePage;
