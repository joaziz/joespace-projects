<?php

namespace Joespace\Projects\Filament\Resources;

use Filament\Facades\Filament;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Joespace\Core\Filament\BaseResource;
use Joespace\Projects\Models\Projects;

class ProjectResource extends BaseResource
{
    protected static ?string $model = Projects::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

//    protected static ?string $navigationGroup = "ACL";

    public static function table(Table $table): Table
    {


        return $table
            ->columns(
                [
                    TextColumn::make("name"),
                    TextColumn::make("state")->badge()->color(fn(Projects $rec): string => $rec->state->Color()),
                    TextColumn::make("Version")->default(fn(Projects $projects) => $projects->options->version),
                    TextColumn::make("Build")->default(fn(Projects $projects) => $projects->options->build),
                    TextColumn::make("description")->limit(25),
                ]
            )
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                Action::make("New Build")
                    ->requiresConfirmation()
                    ->action(function (Projects $rec) {
                        $build = $rec->UpdateBuildAndGet();
                        activity()
                            ->performedOn($rec)
                            ->causedBy(Filament::auth()->user())
                            ->withProperties(['build' => $build])
                            ->log('User update build number');
                        Notification::make("CreateBuild")
                            ->title("Build Created")
                            ->body("the new build is " . $build)->success()->send();
                    }),
                Action::make("New Version")
                    ->form([
                        Select::make("version_part")
                            ->native(false)
                            ->label("Part")->options([
                                "Major" => "Major",
                                "Minor" => "Minor",
                                "Patch" => "Patch"
                            ])
                    ])->action(function (array $data, Projects $rec) {

                        $version = $rec->UpdateVersionAndGet($data["version_part"]);
                        activity()
                            ->performedOn($rec)
                            ->causedBy(Filament::auth()->user())
                            ->withProperties(['type' => $data["version_part"], "version" => $version])
                            ->log('User update build number');


                        Notification::make("CreateVersion")
                            ->title("Version Created")
                            ->body("the new version type is " . $data["version_part"] . "and the number is " . $version)->success()->send();
                    })
                ,
                ActionGroup::make(
                    []
                )
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make("Main Details")
                ->description("Pleae enter the basic details of the project")->schema(
                    [

                        TextInput::make("name")->placeholder("watchit"),
                        Textarea::make("description")->rows(10)->placeholder("a vod project..."),
                    ]
                )
        ]);
    }
}