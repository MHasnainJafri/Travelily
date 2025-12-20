import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link } from '@inertiajs/react';
import { 
  LayoutGrid, 
  Users, 
  MapPin, 
  Shield, 
  Car, 
  Megaphone, 
  CreditCard, 
  Receipt, 
  Plane, 
  Home, 
  Sparkles, 
  CheckSquare, 
  List, 
  Heart,
  Calendar
} from 'lucide-react';
import AppLogo from './app-logo';


const mainNavItems: NavItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
    icon: LayoutGrid,
  },
  {
    title: 'Travellers',
    href: '/admin/users?role=traveller',
    icon: Users,
  },
  {
    title: 'Guides',
    href: '/admin/users?role=guide',
    icon: MapPin,
  },
  {
    title: 'Hosts',
    href: '/admin/users?role=host',
    icon: Home,
  },
  {
    title: 'Admins',
    href: '/admin/users?role=admin',
    icon: Shield,
  },
  {
    title: 'Jamboards',
    href: '/admin/jamboard',
    icon: Car,
  },
  {
    title: 'Trips',
    href: '/admin/trips',
    icon: Plane,
  },
  {
    title: 'Listings',
    href: '/admin/listings',
    icon: Home,
  },
  {
    title: 'Bookings',
    href: '/admin/bookings',
    icon: Calendar,
  },
  {
    title: 'Plans',
    href: '/admin/plans',
    icon: CreditCard,
  },
  {
    title: 'Subscriptions',
    href: '/admin/subscriptions',
    icon: Receipt,
  },
  {
    title: 'Tasks',
    href: '/admin/tasks',
    icon: CheckSquare,
  },
  {
    title: 'Interests',
    href: '/admin/interests',
    icon: Heart,
  },
  {
    title: 'Amenities',
    href: '/admin/amenities',
    icon: Sparkles,
  },
  {
    title: 'Bucket Lists',
    href: '/admin/buckets',
    icon: List,
  },
  {
    title: 'Advertisements',
    href: '/admin/advertisement',
    icon: Megaphone,
  },
]

const footerNavItems: NavItem[] = [
    // {
    //     title: 'Repository',
    //     href: 'https://github.com/laravel/react-starter-kit',
    //     icon: Folder,
    // },
    // {
    //     title: 'Documentation',
    //     href: 'https://laravel.com/docs/starter-kits#react',
    //     icon: BookOpen,
    // },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
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
