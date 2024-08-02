import { Outlet } from "react-router-dom";
import LogoutHandler from "@/LogoutHandler";

// Root component that renders the main content or an outlet for nested routes
function Root({ children }) {
  return (
    <>
      <LogoutHandler />
      <main>
        {children || <Outlet />}
      </main>
    </>
  );
}

export default Root;
