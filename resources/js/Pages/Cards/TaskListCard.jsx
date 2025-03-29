import { Button } from '@headlessui/react';
import { PiSquaresFour } from 'react-icons/pi';

export default function TaskListCard({ tasks }) {
    return (
        <div className="space-y-4 py-6">
            <ul role="list" className="divide-y divide-gray-100 rounded-md border border-gray-200">
                {tasks
                    .filter((task) => task.parent_id === null)
                    .map((task, index) => (
                        <li key={index} className="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                            <div className="flex w-0 flex-1 items-center">
                                <PiSquaresFour className="h-5 w-5 flex-shrink-0 text-muted-foreground" />

                                <div className="ml-4 flex min-w-0 flex-col">
                                    <span className="truncate font-medium">{task.title}</span>
                                </div>
                            </div>
                            <div className="ml-4 flex shrink-0">
                                <Button
                                    variant="link"
                                    className="font-medium text-red-500 hover:text-red-600 hover:no-underline"
                                    onClick={() => console.log('delete task')}
                                >
                                    Delete
                                </Button>
                            </div>
                        </li>
                    ))}
            </ul>
        </div>
    );
}
