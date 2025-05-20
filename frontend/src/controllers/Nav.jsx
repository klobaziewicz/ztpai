import StarIcon  from "@mui/icons-material/Star";
import { AppBar, Box, Button, Toolbar, Typography } from "@mui/material";
import { styled } from "@mui/system";
import React from "react";
import { Link } from 'react-router-dom';

const StyledButton = styled(Button)(({ theme }) => ({
    margin: theme.spacing(1),
    padding: theme.spacing(0.5, 1.5),
    borderRadius: theme.shape.borderRadius,
}));

const Nav = () => {
    return (
        <AppBar
            position="fixed"
            color="default"
            sx={{ borderBottom: 1, borderColor: "divider", width: "100vw", top: 0, display: "flex" }}
        >
            <Toolbar sx={{ display: "flex", justifyContent: "space-between", alignItems: "center", minWidth: "80px" }}>
                <Box sx={{ display: "flex", flex: 1}}>
                    <StarIcon sx={{ fontSize: 59 }} />
                </Box>

                <Box sx={{ display: "flex", justifyContent: "center", flex: 1}}>
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

                <Box sx={{ display: "flex", alignItems: "center", gap: 1, pl: 2, flex: 1 }}>
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