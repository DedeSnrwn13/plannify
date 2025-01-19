import { clsx } from "clsx"
import { twMerge } from "tailwind-merge"

export function cn(...inputs) {
    return twMerge(clsx(inputs))
}

export const STATUS = {
    TODO: 'To do',
    INPROGRESS: 'In Progress',
    ONREVIEW: 'On Review',
    DONE: 'Done',
    UNKNOWN: 'Unknown',
};

export const PRIORITY = {
    URGERNT: 'Urgent',
    HIGH: 'High',
    MEDIUM: 'Medium',
    LOW: 'Low',
    UNKNOWN: 'Unknown',
};
