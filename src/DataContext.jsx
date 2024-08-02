import { createContext, useState, useEffect, useRef } from 'react';
import { toast } from 'react-toastify';
// Create context
export const DataContext = createContext({});

// Provider component
export const DataProvider = ({ children }) => {
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [editMode, setEditMode] = useState(false);
    const [isAuthenticated, setIsAuthenticated] = useState(false);
    const [loggingOut, setLoggingOut] = useState(false);
    let themeImport = useRef(null);

    let defaultNightMode = localStorage.getItem('nightMode') ? localStorage.getItem('nightMode') === 'true' : false;
    const [nightMode, updateNightMode] = useState(defaultNightMode);

    useEffect(() => {
        const applyTheme = async () => {
            const themePath = nightMode 
                ? import.meta.env.BASE_URL + 'assets/themes/lara-dark-blue/theme.css' 
                : import.meta.env.BASE_URL + 'assets/themes/lara-light-blue/theme.css';

            // Remove the old stylesheet
            if (themeImport.current) {
                themeImport.current.remove();
            }

            // Dynamically create a new link element
            const linkElement = document.createElement('link');
            linkElement.rel = 'stylesheet';
            linkElement.href = themePath;
            linkElement.id = 'theme-stylesheet';

            // Append the new stylesheet to the document head
            document.head.appendChild(linkElement);

            themeImport.current = linkElement;
        };

        applyTheme();
    }, [nightMode, themeImport]);

    const setNightMode = (value) => {      
        updateNightMode(value);
    }

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await fetch( import.meta.env.VITE_API_URL + '/data.php');
                if (!response.ok) {
                    // If server response was not ok, throw an error to catch block
                    throw new Error('Failed to connect to API server.');
                }
                const responseJson = await response.json();
                setData(responseJson); // Update data state with fetched data
                
                // Check if user data exists and update nightMode accordingly
                if (responseJson.user) {
                    setNightMode(responseJson.user.night_mode);
                    setIsAuthenticated(true);
                } else {
                    setIsAuthenticated(false);
                }
            } catch (error) {
                toast.error(`Error Loading Data: ${error.message}`);
                setIsAuthenticated(false);
            } finally {
                // Ensure loading is set to false only after data is fetched and processed
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    const fetchDataOnLogin = async () => {
        try {
            const response = await fetch( import.meta.env.VITE_API_URL + '/data.php');
            if (!response.ok) {
                // If server response was not ok, throw an error to catch block
                throw new Error('Failed to connect to API server.');
            }
            const responseJson = await response.json();
            setData(responseJson); // Update data state with fetched data
            
            // Check if user data exists and update nightMode accordingly
            if (responseJson.user) {
                setNightMode(responseJson.user.night_mode);
                setIsAuthenticated(true);
            } else {
                setIsAuthenticated(false);
            }
        } catch (error) {
            toast.error(`Error Loading Data: ${error.message}`);
            setIsAuthenticated(false);
        } finally {
            // Ensure loading is set to false only after data is fetched and processed
            setLoading(false);
        }
    };

    const contextValue = { editMode, data, loading, isAuthenticated, nightMode, loggingOut, setEditMode, setData, setLoading, setNightMode, setIsAuthenticated, setLoggingOut, fetchDataOnLogin };

    return (
        <DataContext.Provider value={contextValue}>
            {children}
        </DataContext.Provider>
    );
};
