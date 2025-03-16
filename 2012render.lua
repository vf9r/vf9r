game:GetService("ContentProvider"):SetBaseUrl("http://www.roblox.com")
game:GetService("ScriptContext").ScriptsDisabled = true
local plr = game.Players:CreateLocalPlayer(0)
plr:LoadCharacter()
for i,v in pairs(plr.Character:GetChildren()) do
  if v:IsA("BasePart") then
    v.Material = Enum.Material.SmoothPlastic
  end
end
print(game:GetService("ThumbnailGenerator"):Click("PNG", 500, 500, true))
