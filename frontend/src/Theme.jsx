import { createTheme } from "@mui/material/styles";

const theme = createTheme({
    palette: {
        primary: {
            main: "#1936f1",
        },
        secondary: {
            main: "#252a43",
        },
        background: {
            default: "#7b84af",
            paper: "#2d3758",
        },
    },
    typography: {
        fontFamily: "'Roboto', 'Helvetica', 'Arial', sans-serif",
        h2: {
            color: "#fff",
        },
        button: {
            textTransform: "none",
        },
    },
    spacing: 8,
});

export default theme;
