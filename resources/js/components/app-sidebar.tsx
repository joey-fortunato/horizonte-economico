import { Link } from '@inertiajs/react';
import {
    BarChart3,
    Globe,
    Image as ImageIcon,
    LayoutGrid,
    Newspaper,
    Tags,
    Users,
} from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    { title: 'Painel', href: dashboard(), icon: LayoutGrid },
    { title: 'Artigos', href: '/artigos', icon: Newspaper },
    { title: 'Categorias', href: '/categorias', icon: Tags },
    { title: 'Media', href: '/media', icon: ImageIcon },
    { title: 'Utilizadores', href: '/utilizadores', icon: Users },
    { title: 'Estatísticas', href: '/estatisticas', icon: BarChart3 },
];

const footerNavItems: NavItem[] = [
    { title: 'Ver o site', href: '/', icon: Globe },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
