import { StatusCardStatic } from '@/components/Cards';
import { useContext, useState, useEffect } from 'react';
import { DataContext } from '@/DataContext';
import { Card, Alert } from 'react-bootstrap';

const ExampleWeatherCard = ({ getUserData, manifest }) => {
  const context = useContext(DataContext);
  const [preferences, setPreferences] = useState({});
  const [weatherData, setWeatherData] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchUserData = async () => {
      const userData = await getUserData(manifest.id, context);
      setPreferences(userData);
    };

    fetchUserData();
  }, [context, getUserData, manifest.id]);

  useEffect(() => {
    const fetchWeatherData = async () => {
      if (!preferences.zipcode) return setError("Zip code is not set in user preferences.");
      setError(null);

      try {
        const geoResponse = await fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${preferences.zipcode}&count=1`);
        const geoData = await geoResponse.json();

        if (geoData.results.length === 0) return setError("No results found for the provided zip code.");

        const { latitude, longitude } = geoData.results[0];
        const weatherResponse = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current_weather=true${preferences.unit != '' ? `&temperature_unit=${preferences.unit}` : ''}`);
        const weatherData = await weatherResponse.json();

        setWeatherData(weatherData.current_weather);
      } catch (err) {
        setError("Failed to fetch weather data.");
      }
    };

    preferences.zipcode ? fetchWeatherData() : setError("Please set your Zip Code in user preferences.");
  }, [preferences]);

  return (
    <StatusCardStatic header={'Weather'}>
      { error 
        ? <Alert variant="danger">{error}</Alert> 
        : weatherData 
          ? (
            <Card.Body>
              <Card.Title>Current Weather</Card.Title>
              <Card.Text>
                Temperature: {weatherData.temperature}°{preferences.unit === 'fahrenheit' ? 'F' : 'C'}
              </Card.Text>
            </Card.Body>
          ) 
          : <Alert variant="info">Loading...</Alert>
      }
    </StatusCardStatic>
  );
};

export default ExampleWeatherCard;
