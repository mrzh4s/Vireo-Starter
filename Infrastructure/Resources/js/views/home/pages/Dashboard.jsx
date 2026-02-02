import { usePage } from '@inertiajs/react';
import { AppLayout } from '@/components/layouts';
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/components/ui/card';
import { User, Mail, Shield, Users } from 'lucide-react';

export default function Dashboard() {
  const { user, stats } = usePage().props;

  return (
    <AppLayout>
      <div className="container mx-auto px-4 py-8">
        <div className="space-y-6">
          {/* Welcome Card */}
          <Card>
            <CardHeader>
              <CardTitle>{stats.welcome_message}</CardTitle>
              <CardDescription>
                Here's an overview of your account
              </CardDescription>
            </CardHeader>
          </Card>

          {/* User Info Grid */}
          <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            {/* User Details */}
            <Card>
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">
                  User Information
                </CardTitle>
                <User className="h-4 w-4 text-muted-foreground" />
              </CardHeader>
              <CardContent>
                <div className="space-y-1">
                  <p className="text-xl font-bold">{user.name}</p>
                  <p className="text-xs text-muted-foreground flex items-center gap-1">
                    <Mail className="h-3 w-3" />
                    {user.email}
                  </p>
                </div>
              </CardContent>
            </Card>

            {/* Roles */}
            <Card>
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">Roles</CardTitle>
                <Shield className="h-4 w-4 text-muted-foreground" />
              </CardHeader>
              <CardContent>
                <div className="space-y-1">
                  {user.roles && user.roles.length > 0 ? (
                    <div className="flex flex-wrap gap-1">
                      {user.roles.map((role, index) => (
                        <span
                          key={index}
                          className="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10"
                        >
                          {role.display_name || role.name}
                        </span>
                      ))}
                    </div>
                  ) : (
                    <p className="text-sm text-muted-foreground">No roles assigned</p>
                  )}
                </div>
              </CardContent>
            </Card>

            {/* Groups */}
            <Card>
              <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium">Groups</CardTitle>
                <Users className="h-4 w-4 text-muted-foreground" />
              </CardHeader>
              <CardContent>
                <div className="space-y-1">
                  {user.groups && user.groups.length > 0 ? (
                    <div className="flex flex-wrap gap-1">
                      {user.groups.map((group, index) => (
                        <span
                          key={index}
                          className="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20"
                        >
                          {group.display_name || group.name}
                        </span>
                      ))}
                    </div>
                  ) : (
                    <p className="text-sm text-muted-foreground">No groups assigned</p>
                  )}
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Additional Info */}
          <Card>
            <CardHeader>
              <CardTitle>Getting Started</CardTitle>
              <CardDescription>
                Quick links and helpful resources
              </CardDescription>
            </CardHeader>
            <CardContent>
              <div className="space-y-2 text-sm">
                <p>• Your account is fully set up and ready to use</p>
                <p>• You can update your profile settings anytime</p>
                <p>• Check back here for important updates and notifications</p>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </AppLayout>
  );
}
