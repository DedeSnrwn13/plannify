import Header from '@/Components/Header';
import Widget from '@/Components/Widget';
import AppLayout from '@/Layouts/AppLayout';
import { usePage } from '@inertiajs/react';
import { BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, Title, Tooltip } from 'chart.js';
import { Bar } from 'react-chartjs-2';
import { PiBriefcase, PiSquaresFour } from 'react-icons/pi';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

export default function Dashboard({ page_settings, count, productivity_chart }) {
    const auth = usePage().props.auth.user;

    const options = {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Productivity Chart',
            },
        },
    };

    const data = {
        labels: productivity_chart.label,
        datasets: productivity_chart.datasets.map((dataset) => ({
            label: dataset.label,
            data: dataset.data,
            backgroundColor: dataset.backgroundColor,
        })),
    };

    return (
        <>
            <div>
                <div className="xl:pr-96">
                    <div>
                        <Header title={page_settings.title} subtitle={page_settings.subtitle} />
                        <div>
                            <dl className="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                                <Widget
                                    icon={<PiBriefcase className="h-6 w-6 text-white" />}
                                    title="Workspace"
                                    count={count.workspaces}
                                    bgColor="bg-gradient-to-b from-green-400 to-green-600"
                                />

                                <Widget
                                    icon={<PiSquaresFour className="h-6 w-6 text-white" />}
                                    title="My Tasks"
                                    count={count.tasks}
                                    bgColor="bg-gradient-to-b from-fuchsia-400 to-fuchsia-600"
                                />

                                <Widget
                                    icon={<PiSquaresFour className="h-6 w-6 text-white" />}
                                    title="Tasks Done"
                                    count={count.dones}
                                    bgColor="bg-gradient-to-b from-sky-400 to-sky-600"
                                />
                            </dl>
                        </div>

                        <div className="mt-8 rounded-lg border bg-white px-4 pb-6 pt-5 sm:px-6 sm:pt-6">
                            <Bar options={options} data={data} />
                        </div>
                    </div>
                </div>
            </div>

            <aside className="fixed inset-y-0 right-0 hidden w-96 overflow-y-auto border-l border-gray-200 px-4 py-6 lg:px-8 xl:block">
                {/* card */}
            </aside>
        </>
    );
}

Dashboard.layout = (page) => <AppLayout children={page} title={page.props.page_settings.title} />;
