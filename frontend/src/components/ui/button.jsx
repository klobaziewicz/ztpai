import React from 'react';

export function Button({ children, variant, className }) {
    return (
        <button className={`${variant} ${className}`}>
            {children}
        </button>
    );
}