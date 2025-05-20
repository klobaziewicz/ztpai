import StarIcon  from "@mui/icons-material/Star";
import { AppBar, Box, Button, Toolbar, Typography } from "@mui/material";
import { styled } from "@mui/system";
import React from "react";
import { Link } from 'react-router-dom';

const StyledButton = styled(Button)(({ theme }) => ({
    margin: theme.spacing(0.5),
    padding: theme.spacing(1,3,1,3),
    borderRadius: theme.shape.borderRadius,
}));

const Nav = () => {
    return (
        <AppBar
            position="fixed"
            color="default"
            sx={{ borderBottom: 1, borderColor: "divider", width: "100vw", top: 0 }}
        >
            <Toolbar sx={{ display: "flex", justifyContent: "space-between" }}>
                <StarIcon sx={{ fontSize: 59 }} />

                <Box sx={{ display: "flex", flexGrow: 1, justifyContent: "center" }}>
                    <Link to={`/`}>
                        <StyledButton variant="contained" color="primary">
                            <Typography>Home</Typography>
                        </StyledButton>
                    </Link>
                    <Link to={`/posts`}>
                        <StyledButton>
                            <Typography>Posts</Typography>
                        </StyledButton>
                    </Link>
                    <Link to={`/userlist`}>
                        <StyledButton>
                            <Typography>Users</Typography>
                        </StyledButton>
                    </Link>
                </Box>

                <Box sx={{ display: "flex" }}>
                    <Link to={`/login`}>
                        <StyledButton variant="outlined">
                            <Typography>Sign in</Typography>
                        </StyledButton>
                    </Link>
                    <Link to={`/register`}>
                        <StyledButton variant="contained" color="primary">
                            <Typography>Register</Typography>
                        </StyledButton>
                    </Link>
                </Box>
            </Toolbar>
        </AppBar>
    );
};

export default Nav;