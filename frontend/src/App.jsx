import { BrowserRouter as Router, Route, Routes } from "react-router-dom";
import Home from "./controllers/Home";
import Login from "./controllers/Login";
import UserList from "./controllers/UserList";
import NotFound from "./controllers/NotFound";
import UserDetail from './controllers/UserDetail';
import CreateUserForm from './controllers/CreateUserForm';
import Register from './controllers/Register';
import Notification from "./controllers/Notification";
import Posts from "./controllers/Posts";
import { ThemeProvider } from "@mui/material/styles";
import CssBaseline from "@mui/material/CssBaseline";
import theme from "./Theme.jsx";

function App() {
    return (
        <ThemeProvider theme={theme}>
            <CssBaseline />
            <Router>
                <Routes>
                    <Route path="/" element={<Home />} />
                    <Route path="/register" element={<Register />} />
                    <Route path="/login" element={<Login />} />
                    <Route path="/userlist" element={<UserList />} />
                    <Route path="/user/:nick" element={<UserDetail />} />
                    <Route path="/create-user" element={<CreateUserForm />} />
                    <Route path="/notification" element={<Notification />} />
                    <Route path="/posts" element={<Posts />} />
                    <Route path="*" element={<NotFound />} />
                </Routes>
            </Router>
        </ThemeProvider>
    );
}

export default App;
