import { useContext, useState, useEffect } from 'react';
import { DataContext } from '@/DataContext';
import { DefaultLayout } from '@/layouts';
import { Alert, Card, Row, Col } from 'react-bootstrap';

const ExampleWeatherPage = ({ getUserData, manifest }) => {
  const context = useContext(DataContext);
  const [preferences, setPreferences] = useState({});
  const [weatherData, setWeatherData] = useState(null);
  const [forecastData, setForecastData] = useState(null);
  const [error, setError] = useState(null);

  const getWeatherDescription = (code) => {
    switch (code) {
      case 0: return "Clear sky";
      case 1: return "Mainly clear";
      case 2: return "Partly cloudy";
      case 3: return "Overcast";
      case 45: return "Fog";
      case 48: return "Depositing rime fog";
      case 51: return "Drizzle: Light intensity";
      case 53: return "Drizzle: Moderate intensity";
      case 55: return "Drizzle: Dense intensity";
      case 56: return "Freezing Drizzle: Light intensity";
      case 57: return "Freezing Drizzle: Dense intensity";
      case 61: return "Rain: Slight intensity";
      case 63: return "Rain: Moderate intensity";
      case 65: return "Rain: Heavy intensity";
      case 66: return "Freezing Rain: Light intensity";
      case 67: return "Freezing Rain: Heavy intensity";
      case 71: return "Snow fall: Slight intensity";
      case 73: return "Snow fall: Moderate intensity";
      case 75: return "Snow fall: Heavy intensity";
      case 77: return "Snow grains";
      case 80: return "Rain showers: Slight intensity";
      case 81: return "Rain showers: Moderate intensity";
      case 82: return "Rain showers: Violent intensity";
      case 85: return "Snow showers: Slight intensity";
      case 86: return "Snow showers: Heavy intensity";
      case 95: return "Thunderstorm: Slight or moderate";
      case 96: return "Thunderstorm with slight hail";
      case 99: return "Thunderstorm with heavy hail";
      default: return "Unknown weather code";
    }
  };  

  const getDayName = (index) => {
    const date = new Date();
    date.setDate(date.getDate() + index);
    return date.toLocaleDateString('en-US', { weekday: 'long' });
  };

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
        const unit = preferences.unit || 'metric';
        const weatherResponse = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current_weather=true&daily=temperature_2m_min,temperature_2m_max&timezone=auto&temperature_unit=${unit}`);
        const weatherData = await weatherResponse.json();

        setWeatherData(weatherData.current_weather);
        setForecastData(weatherData.daily);
      } catch (err) {
        setError("Failed to fetch weather data.");
      }
    };

    preferences.zipcode ? fetchWeatherData() : setError("Please set your Zip Code in user preferences.");
  }, [preferences]);

  return (
    <DefaultLayout>
      <div className={`p-5 mb-4 rounded-3 ${context.nightMode ? "bg-dark" : ""}`}>
        <h2>Current Weather</h2>
        { error 
        ? <Alert variant="danger">{error}</Alert> 
        : weatherData 
          ? <>
              <Card data-bs-theme={context.nightMode ? 'dark' : undefined}>
                <Card.Body>
                  <Card.Text>
                    Temperature: {weatherData.temperature}°{preferences.unit === 'fahrenheit' ? 'F' : 'C'}
                    <br />
                    Wind Speed: {weatherData.windspeed} {preferences.unit === 'fahrenheit' ? 'mph' : 'm/s'}
                    <br />
                    Conditions: {getWeatherDescription(weatherData.weathercode)}
                  </Card.Text>
                </Card.Body>
              </Card>
              <h2 className="mt-4">7-Day Forecast</h2>
              <Row>
              {forecastData.temperature_2m_min && forecastData.temperature_2m_min.map((minTemp, index) => (
                  <Col key={index} sm={6} md={4} lg={3} className="mb-4">
                    <Card data-bs-theme={context.nightMode ? 'dark' : undefined}>
                      <Card.Body>
                        <Card.Title>{getDayName(index)}</Card.Title>
                        <Card.Text>
                          Min Temp: {minTemp}°{preferences.unit === 'fahrenheit' ? 'F' : 'C'}
                          <br />
                          Max Temp: {forecastData.temperature_2m_max[index]}°{preferences.unit === 'fahrenheit' ? 'F' : 'C'}
                        </Card.Text>
                      </Card.Body>
                    </Card>
                  </Col>
                ))}
              </Row>
            </>
          : <Alert variant="info">Loading...</Alert>
        }
      </div>
    </DefaultLayout>
  );
};

export default ExampleWeatherPage;
