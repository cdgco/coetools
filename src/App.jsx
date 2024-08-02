import { useContext, useEffect, } from "react";
import { createHashRouter, RouterProvider } from "react-router-dom";
import { Root } from "@/components";
import {
  DirectoryPage,
  ErrorPage,
  Frame,
  Home,
  LoadingPage,
  LoginPage,
  Settings,
} from "@/pages";
import { DataContext } from "@/DataContext";
import { ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { ProtectedRoute } from "@/components";
import { routerPageLoader } from "@/extensions";
const App = () => {
  const { loading, nightMode } = useContext(DataContext);

  useEffect(() => {
    localStorage.setItem("nightMode", nightMode);
  }, [nightMode]);

  if (loading) {
    return <LoadingPage />;
  }

  // Define your routes as before
  const router = createHashRouter([
    {
      path: "/",
      element: <Root />,
      errorElement: (
        <Root>
          <ErrorPage />
        </Root>
      ),
      children: [
        { index: true, element: <ProtectedRoute><Home /></ProtectedRoute> },
        { path: "directory", element: <ProtectedRoute><DirectoryPage /></ProtectedRoute> },
        { path: "settings", element: <ProtectedRoute><Settings /></ProtectedRoute> },
        { path: "frame", element: <ProtectedRoute><Frame /></ProtectedRoute> },
        { path: "error", element: <ErrorPage /> },
        { path: "login", element: <LoginPage /> },
        ...routerPageLoader(),
      ],
    },
  ]);

  return (
    <>
      <RouterProvider router={router} />
      <ToastContainer />
    </>
  );
};

export default App;
