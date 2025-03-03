import React from 'react';

export function NavigationMenu({ children, className }) {
    return <nav className={`${className}`}>{children}</nav>;
}

export function NavigationMenuList({ children, className }) {
    return <ul className={`${className}`}>{children}</ul>;
}

export function NavigationMenuItem({ children, className }) {
    return <li className={`${className}`}>{children}</li>;
}

export function NavigationMenuLink({ children, className }) {
    return <a className={`${className}`}>{children}</a>;
}