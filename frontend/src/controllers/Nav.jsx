import block from "@mui/icons-material/Star";
import { AppBar, Box, Button, Toolbar, Typography } from "@mui/material";
import { styled } from "@mui/system";
import React from "react";

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
                <Box component="img" src={block} alt="Block" sx={{ height: 59 }} />

                <Box sx={{ display: "flex", flexGrow: 1, justifyContent: "center" }}>
                    <StyledButton variant="contained" color="primary">
                        <Typography>Home</Typography>
                    </StyledButton>
                    <StyledButton>
                        <Typography>Groups</Typography>
                    </StyledButton>
                    <StyledButton>
                        <Typography>Community</Typography>
                    </StyledButton>
                    <StyledButton>
                        <Typography>Chat</Typography>
                    </StyledButton>
                    <StyledButton>
                        <Typography>Contact</Typography>
                    </StyledButton>
                </Box>

                <Box sx={{ display: "flex" }}>
                    <StyledButton variant="outlined">
                        <Typography>Sign in</Typography>
                    </StyledButton>
                    <StyledButton variant="contained" color="primary">
                        <Typography>Register</Typography>
                    </StyledButton>
                </Box>
            </Toolbar>
        </AppBar>
    );
};

export default Nav;